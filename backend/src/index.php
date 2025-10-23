<?php
require_once __DIR__ . '/api/CsvUploadApi.php';

$api = new CsvUploadApi();
$api->handleUpload();