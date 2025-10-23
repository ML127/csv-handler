<?php

class CsvValidator
{
    private array $errors = [];

    public function validateFile(array $file): bool
    {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'Invalid file upload.';
            return false;
        }

        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, ['text/plain', 'text/csv', 'application/vnd.ms-excel'])) {
            $this->errors[] = 'Uploaded file is not a valid CSV.';
            return false;
        }

        return true;
    }

    public function validateRow(array $row, int $lineNumber): bool
    {
        if (count($row) < 4) {
            $this->errors[] = "Line {$lineNumber}: Missing required columns.";
            return false;
        }

        [$companyName, $employeeName, $email, $salary] = $row;

        if (empty($companyName) || empty($employeeName) || empty($email) || empty($salary)) {
            $this->errors[] = "Line {$lineNumber}: One or more required fields are empty.";
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Line {$lineNumber}: Invalid email format ({$email}).";
            return false;
        }

        if (!is_numeric($salary) || $salary <= 0) {
            $this->errors[] = "Line {$lineNumber}: Invalid salary ({$salary}).";
            return false;
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
