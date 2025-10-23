<?php
require_once __DIR__ . '/../config/database.php';

class GetEmployeesApi
{
    private PDO $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function handleGet(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Only GET requests are allowed.']);
            return;
        }

        try {
            $query = "
                SELECT e.id, e.employee_name, e.email, e.salary, c.name AS company
                FROM employees e
                JOIN companies c ON e.company_id = c.id
                ORDER BY c.name, e.employee_name
            ";
            $stmt = $this->conn->query($query);
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'employees' => $employees]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database query failed', 'details' => $e->getMessage()]);
        }
    }
}
