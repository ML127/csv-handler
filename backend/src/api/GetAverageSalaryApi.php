<?php
require_once __DIR__ . '/../config/database.php';

class GetAverageSalaryApi
{
    private PDO $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function handleGet(): void
    {
        $query = "
            SELECT c.name AS company, 
                   ROUND(AVG(e.salary), 2) AS avg_salary
            FROM employees e
            JOIN companies c ON e.company_id = c.id
            GROUP BY c.id, c.name
            ORDER BY c.name ASC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'averages' => $result
        ]);
    }
}
