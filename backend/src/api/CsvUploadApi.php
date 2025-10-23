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

        if (!$this->csvValidator->validateFile($_FILES['csvFile'] ?? [])) {
            $this->respond(400, [
                'error'   => 'Invalid CSV upload.',
                'details' => $this->csvValidator->getErrors(),
            ]);
            return;
        }

        $filePath = $_FILES['csvFile']['tmp_name'] ?? null;
        $handle   = $filePath ? fopen($filePath, 'r') : false;

        if ($handle === false) {
            $this->respond(400, ['error' => 'Unable to open uploaded file.']);
            return;
        }

        // Skip header line
        fgetcsv($handle);

        [$insertedCompanies, $insertedEmployees, $skippedDuplicates] = $this->processCsv($handle);
        fclose($handle);

        $this->respond(200, [
            'success'             => true,
            'companies_inserted'  => $insertedCompanies,
            'employees_inserted'  => $insertedEmployees,
            'duplicates_skipped'  => $skippedDuplicates,
            'errors'              => $this->csvValidator->getErrors(),
        ]);
    }

    /**
     * @param resource $handle
     * @return array{0:int,1:int,2:int}
     */
    private function processCsv($handle): array
    {
        $insertedCompanies  = 0;
        $insertedEmployees  = 0;
        $skippedDuplicates  = 0;
        $line               = 1;
        $companiesCache     = [];

        $stmtCompanyInsert  = $this->conn->prepare("INSERT IGNORE INTO companies (name) VALUES (?)");
        $stmtCompanyGetId   = $this->conn->prepare("SELECT id FROM companies WHERE name = ?");
        $stmtEmployeeCheck  = $this->conn->prepare("SELECT id FROM employees WHERE email = ?");
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

            $companyId = $this->getCompanyId(
                $companyName,
                $stmtCompanyInsert,
                $stmtCompanyGetId,
                $companiesCache,
                $insertedCompanies
            );

            if (!$companyId) {
                continue;
            }

            if ($this->employeeExists($email, $stmtEmployeeCheck)) {
                $skippedDuplicates++;
                continue;
            }

            if ($this->insertEmployee($stmtEmployeeInsert, $companyId, $employeeName, $email, $salary)) {
                $insertedEmployees++;
            }
        }

        return [$insertedCompanies, $insertedEmployees, $skippedDuplicates];
    }

    /**
     * Resolve a company ID with caching and count new inserts.
     */
    private function getCompanyId(
        string $name,
        PDOStatement $insertStmt,
        PDOStatement $getIdStmt,
        array &$cache,
        int &$insertCount
    ): ?int {
        if (!isset($cache[$name])) {
            $insertStmt->execute([$name]);
            if ($insertStmt->rowCount() > 0) {
                $insertCount++;
            }
            $getIdStmt->execute([$name]);
            $cache[$name] = $getIdStmt->fetchColumn();
        }
        return $cache[$name] !== false ? (int)$cache[$name] : null;
    }

    /**
     * Check if an employee email already exists.
     */
    private function employeeExists(string $email, PDOStatement $stmt): bool
    {
        $stmt->execute([$email]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Insert an employee row, with safe error logging.
     */
    private function insertEmployee(PDOStatement $stmt, int $companyId, string $name, string $email, $salary): bool
    {
        try {
            return $stmt->execute([$companyId, $name, $email, $salary]);
        } catch (PDOException $e) {
            error_log("Employee insert failed: " . $e->getMessage());
            return false;
        }
    }

    private function respond(int $status, array $data): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
