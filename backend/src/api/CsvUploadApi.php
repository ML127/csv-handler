<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../validators/CsvValidator.php';
require_once __DIR__ . '/../validators/EmailValidator.php';

class CsvUploadApi
{
    private PDO $conn;
    private CsvValidator $csvValidator;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
        $this->csvValidator = new CsvValidator();
    }

    public function handleUpload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(405, ['error' => 'Only POST requests are allowed.']);
            return;
        }

        if (!$this->csvValidator->validateFile($_FILES['csvFile'])) {
            $this->respond(400, [
                'error' => 'Invalid CSV upload.',
                'details' => $this->csvValidator->getErrors()
            ]);
            return;
        }

        $filePath = $_FILES['csvFile']['tmp_name'];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            $this->respond(400, ['error' => 'Unable to open uploaded file.']);
            return;
        }

        fgetcsv($handle); // Skip header

        $insertedCompanies = 0;
        $insertedEmployees = 0;
        $skippedDuplicates = 0;
        $line = 1;
        $companiesCache = [];

        $stmtCompanyInsert = $this->conn->prepare("INSERT IGNORE INTO companies (name) VALUES (?)");
        $stmtCompanyGetId = $this->conn->prepare("SELECT id FROM companies WHERE name = ?");
        $stmtEmployeeCheck = $this->conn->prepare("SELECT id FROM employees WHERE email = ?");
        $stmtEmployeeInsert = $this->conn->prepare("
            INSERT INTO employees (company_id, employee_name, email, salary)
            VALUES (?, ?, ?, ?)
        ");

        while (($row = fgetcsv($handle)) !== false) {
            $line++;
            if (!$this->csvValidator->validateRow($row, $line)) {
                continue;
            }

            [$companyName, $employeeName, $email, $salary] = $row;
            $email = EmailValidator::sanitize($email);

            if (!isset($companiesCache[$companyName])) {
                $stmtCompanyInsert->execute([$companyName]);
                $stmtCompanyGetId->execute([$companyName]);
                $companyId = $stmtCompanyGetId->fetchColumn();

                if ($stmtCompanyInsert->rowCount() > 0) {
                    $insertedCompanies++;
                }

                $companiesCache[$companyName] = $companyId;
            }

            $companyId = $companiesCache[$companyName];
            $stmtEmployeeCheck->execute([$email]);
            $exists = $stmtEmployeeCheck->fetchColumn();

            if ($exists) {
                $skippedDuplicates++;
                continue;
            }

            try {
                $stmtEmployeeInsert->execute([$companyId, $employeeName, $email, $salary]);
                $insertedEmployees++;
            } catch (PDOException $e) {
                error_log("Insert failed: " . $e->getMessage());
            }
        }

        fclose($handle);

        $this->respond(200, [
            'success' => true,
            'companies_inserted' => $insertedCompanies,
            'employees_inserted' => $insertedEmployees,
            'duplicates_skipped' => $skippedDuplicates,
            'errors' => $this->csvValidator->getErrors()
        ]);
    }

    private function respond(int $status, array $data): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
