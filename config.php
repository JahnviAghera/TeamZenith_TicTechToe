<?php
// Display all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "sql207.infinityfree.com";
$dbname = "if0_38725960_spendwise";
$user = "if0_38725960";
$pass = "xAug0MJ9zyags"; // Replace with actual password

$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
