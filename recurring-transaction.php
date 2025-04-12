<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (float)$_POST['amount'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $transaction_type = $_POST['transaction_type'];
    $frequency = $_POST['frequency'];
    $start_date = $_POST['start_date'];
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $payment_method = $_POST['payment_method'];
    
    // Insert recurring transaction
    $stmt = $conn->prepare("INSERT INTO recurring_transactions 
        (user_id, category_id, amount, description, transaction_type, frequency, 
        start_date, end_date, next_occurrence, payment_method)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidsssssss", $user_id, $category_id, $amount, $description, 
        $transaction_type, $frequency, $start_date, $end_date, $start_date, $payment_method);
    
    if ($stmt->execute()) {
        $success = "Recurring transaction created successfully!";
    } else {
        $error = "Error creating recurring transaction: " . $conn->error;
    }
}

// Fetch categories
$stmt = $conn->prepare("SELECT * FROM categories WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Fetch payment methods
$stmt = $conn->prepare("SELECT * FROM payment_methods WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$payment_methods = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Recurring Transaction - SpendWise</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Create Recurring Transaction</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded-lg shadow-md space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <input type="text" name="description" required 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Amount</label>
            <input type="number" step="0.01" name="amount" required 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Type</label>
            <select name="transaction_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="expense">Expense</option>
                <option value="income">Income</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Frequency</label>
            <select name="frequency" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
            <select name="payment_method" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <?php foreach ($payment_methods as $method): ?>
                    <option value="<?= htmlspecialchars($method['name']) ?>"><?= htmlspecialchars($method['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" name="start_date" required 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">End Date (optional)</label>
            <input type="date" name="end_date" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <div class="flex justify-end gap-3">
            <a href="dashboard.php" class="bg-gray-200 px-4 py-2 rounded-md">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create Recurring Transaction</button>
        </div>
    </form>
</body>
</html>