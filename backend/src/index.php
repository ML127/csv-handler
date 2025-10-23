<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// clean the request path
$requestPath = preg_replace('#^/index\.php#', '', $requestPath);
if ($requestPath === '') {
    $requestPath = '/';
}

error_log("URI CHECK (cleaned): " . $requestPath);

switch (true) {

    // Root route — just show available endpoints
    case $requestPath === '/':
        header('Content-Type: application/json');
        echo json_encode([
            'message' => 'CSV Handler API — available endpoints: /api/employees, /api/companies/averages'
        ]);
        break;

    // POST /api/employees — CSV upload
    case $requestPath === '/api/employees' && $_SERVER['REQUEST_METHOD'] === 'POST':
        require_once __DIR__ . '/api/CsvUploadApi.php';
        (new CsvUploadApi())->handleUpload();
        break;

    // GET /api/employees — get all employees
    case $requestPath === '/api/employees' && $_SERVER['REQUEST_METHOD'] === 'GET':
        require_once __DIR__ . '/api/GetEmployeesApi.php';
        (new GetEmployeesApi())->handleGet();
        break;

    // PUT or PATCH /api/employees/{id} — update employee email
    case preg_match('#^/api/employees/(\d+)$#', $requestPath, $matches):
        require_once __DIR__ . '/api/UpdateEmployeeApi.php';
        (new UpdateEmployeeApi())->handleUpdate((int)$matches[1]);
        break;

    // GET /api/companies/averages — average salary per company
    case $requestPath === '/api/companies/averages':
        require_once __DIR__ . '/api/GetAverageSalaryApi.php';
        (new GetAverageSalaryApi())->handleGet();
        break;

    // fallback
    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Endpoint not found',
            'uri' => $requestPath
        ]);
        break;
}
