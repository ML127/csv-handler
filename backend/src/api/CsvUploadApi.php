<?php
require_once __DIR__ . '/../config/database.php';

class CsvUploadApi
{
    private PDO $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function handleUpload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Only POST requests are allowed.']);
            exit;
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'CSV file upload failed.']);
            exit;
        }

        $filePath = $_FILES['file']['tmp_name'];

        echo $filePath;

    }
}
