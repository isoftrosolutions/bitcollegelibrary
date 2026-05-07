<?php
require_once '../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Get search query
$search = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';

if (empty($search)) {
    echo json_encode(['users' => [], 'books' => [], 'rentals' => []]);
    exit;
}

// Perform search
$results = search_everything($search);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($results);
?>