<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../validators/EmailValidator.php';

class UpdateEmployeeApi
{
    private PDO $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function handleUpdate(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            http_response_code(405);
            echo json_encode(['error' => 'Only PUT or PATCH requests are allowed.']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing email field.']);
            return;
        }

        $email = EmailValidator::sanitize($data['email']);
        if (!EmailValidator::isValid($email)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email format.']);
            return;
        }

        // Check for duplicates
        $checkStmt = $this->conn->prepare("SELECT id FROM employees WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $id]);
        if ($checkStmt->fetch()) {
            http_response_code(409);
            echo json_encode(['error' => 'Email already exists for another employee.']);
            return;
        }

        // Update email
        $stmt = $this->conn->prepare("UPDATE employees SET email = ? WHERE id = ?");
        $success = $stmt->execute([$email, $id]);

        if ($success) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Email updated successfully.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Database update failed.'
            ]);
        }
    }
}
