<?php
// budget_alerts.php - Run this daily via cron
require 'config.php';

// Function to create budget alerts
function check_budget_alerts() {
    global $conn;
    
    // Get all active budgets
    $stmt = $conn->prepare("
        SELECT b.*, c.name as category_name, u.user_id, u.email, u.first_name
        FROM budgets b
        JOIN categories c ON b.category_id = c.category_id
        JOIN users u ON b.user_id = u.user_id
        WHERE (b.end_date IS NULL OR b.end_date >= CURDATE())
        AND b.start_date <= CURDATE()
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($budget = $result->fetch_assoc()) {
        // Calculate current spending for this budget's category
        $stmt2 = $conn->prepare("
            SELECT SUM(amount) as spent
            FROM transactions
            WHERE user_id = ? 
            AND category_id = ?
            AND transaction_type = 'expense'
            AND CASE 
                WHEN ? = 'daily' THEN DATE(transaction_date) = CURDATE()
                WHEN ? = 'weekly' THEN YEARWEEK(transaction_date) = YEARWEEK(CURDATE())
                WHEN ? = 'monthly' THEN MONTH(transaction_date) = MONTH(CURDATE()) AND YEAR(transaction_date) = YEAR(CURDATE())
                WHEN ? = 'yearly' THEN YEAR(transaction_date) = YEAR(CURDATE())
            END
        ");
        $stmt2->bind_param("iissss", $budget['user_id'], $budget['category_id'], 
                         $budget['period'], $budget['period'], $budget['period'], $budget['period']);
        $stmt2->execute();
        $spent_result = $stmt2->get_result();
        $spent = $spent_result->fetch_assoc()['spent'] ?? 0;
        
        // Calculate percentage of budget used
        $budget_percent = ($spent / $budget['amount']) * 100;
        
        // Create alert if over threshold
        if ($budget_percent >= 80 && $budget_percent < 90) {
            create_notification(
                $budget['user_id'], 
                "You've used 80% of your budget for {$budget['category_name']}!",
                'budget_alert'
            );
        } elseif ($budget_percent >= 90 && $budget_percent < 100) {
            create_notification(
                $budget['user_id'], 
                "Warning: You've used 90% of your budget for {$budget['category_name']}!",
                'budget_warning'
            );
        } elseif ($budget_percent >= 100) {
            create_notification(
                $budget['user_id'], 
                "Alert: You've exceeded your budget for {$budget['category_name']}!",
                'budget_exceeded'
            );
        }
    }
}

// Function to create notification
function create_notification($user_id, $message, $type) {
    global $conn;
    
    // Check if similar notification exists in last 24 hours
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count FROM notifications 
        WHERE user_id = ? AND message = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)
    ");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->fetch_assoc()['count'] > 0;
    
    // Only create if no similar notification exists
    if (!$exists) {
        $stmt = $conn->prepare("
            INSERT INTO notifications (user_id, message, type)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iss", $user_id, $message, $type);
        $stmt->execute();
    }
}

// Run the budget check
check_budget_alerts();

echo "Budget alerts check completed at " . date('Y-m-d H:i:s');