<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch budgets with category names
$query = "SELECT b.*, c.name as category_name 
          FROM budgets b
          JOIN categories c ON b.category_id = c.category_id
          WHERE b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$budgets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Budgets - SpendWise</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">My Budgets</h1>
        <a href="add-budget.php" class="bg-green-600 text-white px-4 py-2 rounded-md">New Budget</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Range</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($budgets as $budget): ?>
                <tr>
                    <td class="px-6 py-4"><?= htmlspecialchars($budget['category_name']) ?></td>
                    <td class="px-6 py-4 text-right">$<?= number_format($budget['amount'], 2) ?></td>
                    <td class="px-6 py-4"><?= ucfirst($budget['period']) ?></td>
                    <td class="px-6 py-4">
                        <?= date('M j, Y', strtotime($budget['start_date'])) ?>
                        <?= $budget['end_date'] ? ' - ' . date('M j, Y', strtotime($budget['end_date'])) : '' ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="edit-budget.php?id=<?= $budget['budget_id'] ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <a href="delete-budget.php?id=<?= $budget['budget_id'] ?>" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>