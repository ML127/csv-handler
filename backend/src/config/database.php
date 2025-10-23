<?php
class Database {
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    public function __construct() {
        // Read from environment variables (set in docker-compose.yml)
        $this->host = getenv('PHP_DB_HOST') ?: 'db';
        $this->db_name = getenv('PHP_DB_NAME') ?: 'employees';
        $this->username = getenv('PHP_DB_USER') ?: 'root';
        $this->password = getenv('PHP_DB_PASS') ?: 'root';
    }

    public function connect(): PDO {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Debug log to confirm
            error_log("Connected to database '{$this->db_name}' at host '{$this->host}'");
            return $this->conn;

        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            echo json_encode(['error' => 'Database connection failed.']);
            exit;
        }
    }
}
