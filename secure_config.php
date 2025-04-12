<?php
// secure_config.php - Place this file outside web root if possible

// Hide errors in production
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
} else {
    // Display errors only in development
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Database credentials - should be in environment variables in production
$db_config = [
    'host' => 'sql207.infinityfree.com',
    'dbname' => 'if0_38725960_spendwise',
    'user' => 'if0_38725960',
    'pass' => 'xAug0MJ9zyags' // In production, use getenv() to get from environment
];

// Create connection 
function get_db_connection() {
    global $db_config;
    
    $conn = new mysqli(
        $db_config['host'], 
        $db_config['user'], 
        $db_config['pass'], 
        $db_config['dbname']
    );
    
    // Check connection
    if ($conn->connect_error) {
        // Log error to file instead of showing it
        error_log("Connection failed: " . $conn->connect_error);
        // Show generic error to user
        die("Database connection error. Please try again later.");
    }
    
    return $conn;
}

// Get connection
$conn = get_db_connection();