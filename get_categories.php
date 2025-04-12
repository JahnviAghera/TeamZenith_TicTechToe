<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = trim($_GET['query'] ?? '');

// Debugging: Uncomment to see received query
// error_log("Search query: $query");

$stmt = $conn->prepare("
    SELECT category_id, name 
    FROM categories 
    WHERE user_id = ? 
    AND LOWER(name) LIKE CONCAT('%', LOWER(?), '%')
    LIMIT 5
");
$stmt->bind_param("is", $user_id, $query);
$stmt->execute();

$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Debugging: Uncomment to see results
// error_log(print_r($categories, true));

header('Content-Type: application/json');
echo json_encode($categories);