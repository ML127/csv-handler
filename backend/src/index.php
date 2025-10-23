<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/api/CsvUploadApi.php';

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($requestUri, PHP_URL_PATH);
error_log("URI: " . $requestPath);

// handle both /api/upload and /index.php/api/upload
if (str_ends_with($requestPath, '/api/upload') || str_contains($requestPath, 'index.php/api/upload')) {
    $api = new CsvUploadApi();
    $api->handleUpload();
    exit;
}

http_response_code(404);
echo json_encode([
    'error' => 'Endpoint not found',
    'uri' => $requestPath
]);
