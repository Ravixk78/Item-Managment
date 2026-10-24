<?php
// Set headers for RESTful API responses
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Path to data file
$dataFile = __DIR__ . '/data.json';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Helper function to read items from data.json
function getItems($filePath) {
    if (!file_exists($filePath)) {
        file_put_contents($filePath, json_encode([]));
        return [];
    }
    $content = file_get_contents($filePath);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

// 1. READ endpoint (GET) - Returns JSON array, status 200
if ($requestMethod === 'GET') {
    $items = getItems($dataFile);
    http_response_code(200);
    echo json_encode($items);
    exit;
}

// 2. CREATE endpoint (POST) - Validates, saves, returns status 201
if ($requestMethod === 'POST') {
    // Read input from php://input (JSON) or $_POST (Form data)
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    if (!is_array($input)) {
        $input = $_POST;
    }

    // Validation: check for required field 'name'
    $name = isset($input['name']) ? trim($input['name']) : '';
    if ($name === '') {
        // Return 400 Bad Request if validation fails
        http_response_code(400);
        echo json_encode([
            "error" => "Validation failed: 'name' is required and cannot be empty."
        ]);
        exit;
    }

    $description = isset($input['description']) ? trim($input['description']) : '';

    // Load existing items
    $items = getItems($dataFile);

    // Calculate new ID
    $maxId = 0;
    foreach ($items as $item) {
        if (isset($item['id']) && is_numeric($item['id']) && $item['id'] > $maxId) {
            $maxId = (int)$item['id'];
        }
    }
    $newId = $maxId + 1;

    // Build new item
    $newItem = [
        "id" => $newId,
        "name" => $name
    ];
    if ($description !== '') {
        $newItem["description"] = $description;
    }

    // Append and save to data.json
    $items[] = $newItem;
    $saveResult = file_put_contents($dataFile, json_encode($items, JSON_PRETTY_PRINT));

    if ($saveResult === false) {
        http_response_code(500);
        echo json_encode([
            "error" => "Internal Server Error: Unable to write to data.json."
        ]);
        exit;
    }

    // Return 201 Created and the new item
    http_response_code(201);
    echo json_encode($newItem);
    exit;
}

// 3. Validation for HTTP method - Return 405 Method Not Allowed for unsupported methods
http_response_code(405);
header("Allow: GET, POST");
echo json_encode([
    "error" => "Method Not Allowed. Only GET and POST requests are supported."
]);
exit;
