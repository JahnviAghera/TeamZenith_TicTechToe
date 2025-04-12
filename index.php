<?php
// Start the session
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect to dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
?>