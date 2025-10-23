<?php
class Database {
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    public PDO $conn;

    public function __construct() {
        // Read database credentials from environment variables docker
        $this->host = getenv('PHP_DB_HOST') ?: 'db';
        $this->db_name = getenv('PHP_DB_NAME') ?: 'employees';
        $this->username = getenv('PHP_DB_USER') ?: 'root';
        $this->password = getenv('PHP_DB_PASS') ?: 'root';
    }

    public function connect(): PDO {
        if (isset($this->conn)) {
            return $this->conn;
        }

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Connected successfully';
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Database connection failed.'
            ]);
            error_log('Database Connection Error: ' . $e->getMessage());
            exit;
        }

        return $this->conn;
    }
}
