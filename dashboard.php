<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Display all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "sql207.infinityfree.com";
$dbname = "if0_38725960_spendwise";
$user = "if0_38725960";
$pass = "xAug0MJ9zyags"; // Replace with actual password

$pdo = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($pdo->connect_error) {
    die("Connection failed: " . $pdo->connect_error); // Use $pdo->connect_error, not $conn
}

$user_id = $_SESSION['user_id'];

try {
    // Current Month's Spend
    $stmt = $pdo->prepare("
        SELECT SUM(amount) AS total 
        FROM transactions 
        WHERE user_id = ? 
        AND transaction_type = 'expense'
        AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
        AND YEAR(transaction_date) = YEAR(CURRENT_DATE())
    ");
    $stmt->bind_param("i", $user_id); // binding the user_id as integer
    $stmt->execute();
    $result = $stmt->get_result();  // Get the result set
    $current_spend = $result->fetch_assoc()['total'] ?? 0; // Correct usage of fetch_assoc()

    // Monthly Budget
    $stmt = $pdo->prepare("
        SELECT SUM(amount) AS total 
        FROM budgets 
        WHERE user_id = ? 
        AND period = 'monthly'
        AND (start_date <= CURDATE() AND (end_date IS NULL OR end_date >= CURDATE()))
    ");
    $stmt->bind_param("i", $user_id); // binding the user_id as integer
    $stmt->execute();
    $result = $stmt->get_result();
    $monthly_budget = $result->fetch_assoc()['total'] ?? 0;  // fetch_assoc for mysqli

    // Recent Transactions (last 5)
    $stmt = $pdo->prepare("
        SELECT t.*, c.name AS category_name, c.color 
        FROM transactions t
        LEFT JOIN categories c ON t.category_id = c.category_id
        WHERE t.user_id = ? 
        ORDER BY t.transaction_date DESC
        LIMIT 5
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;  // Collect transactions
    }

    // Budgets with Progress
    $stmt = $pdo->prepare("
        SELECT b.*, c.name AS category_name,
        (SELECT COALESCE(SUM(amount), 0)
         FROM transactions
         WHERE user_id = b.user_id
         AND category_id = b.category_id
         AND transaction_type = 'expense'
         AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
         AND YEAR(transaction_date) = YEAR(CURRENT_DATE())) AS spent
        FROM budgets b
        LEFT JOIN categories c ON b.category_id = c.category_id
        WHERE b.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $budgets = [];
    while ($row = $result->fetch_assoc()) {
        $budgets[] = $row;  // Collect budgets
    }

    // Financial Goals
    $stmt = $pdo->prepare("SELECT * FROM financial_goals WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $goals = [];
    while ($row = $result->fetch_assoc()) {
        $goals[] = $row;  // Collect goals
    }

    // Notifications
    $stmt = $pdo->prepare("
        SELECT * FROM notifications 
        WHERE user_id = ? AND is_read = 0
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;  // Collect notifications
    }

    // Category Spending
    $stmt = $pdo->prepare("
        SELECT c.name, SUM(t.amount) AS total 
        FROM transactions t
        JOIN categories c ON t.category_id = c.category_id
        WHERE t.user_id = ? 
        AND t.transaction_type = 'expense'
        AND MONTH(t.transaction_date) = MONTH(CURRENT_DATE())
        AND YEAR(t.transaction_date) = YEAR(CURRENT_DATE())
        GROUP BY c.name
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;  // Collect category data
    }

} catch (mysqli_sql_exception $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Get user data from session
$first_name  = $_SESSION['first_name'] ?? 'User';
$last_name   = $_SESSION['last_name'] ?? '';
$email       = $_SESSION['email'] ?? '';
$username    = $_SESSION['username'] ?? '';
$currency    = $_SESSION['currency'] ?? 'USD';
$profile_img = $_SESSION['profile_img'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - SpendWise</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" /
</head>
<body class="bg-gray-100 min-h-screen p-4 pb-24">

  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Welcome back, <?= htmlspecialchars($first_name) ?> 👋</h1>
    
<div class="flex gap-4">
<!-- User Photo -->
<div class="relative group" id="profileDropdown">
  <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden flex items-center justify-center cursor-pointer" id="profileTrigger">
    <?php if ($profile_img): ?>
      <img src="<?= htmlspecialchars($profile_img) ?>" alt="Profile" class="w-full h-full object-cover">
    <?php else: ?>
      <span class="text-xl font-semibold text-gray-600"><?= strtoupper(substr($first_name, 0, 1)) ?></span>
    <?php endif; ?>
  </div>

  <!-- Dropdown Menu -->
  <div class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 z-50" 
       id="profileMenu">
    <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
    <a href="settings.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
    <form method="POST" action="logout.php">
      <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
        Logout
      </button>
    </form>
  </div>
</div>

<script>

// Monthly Spending Trends Chart - Add to dashboard.php
const monthlyCtx = document.createElement('canvas');
monthlyCtx.id = 'monthlyChart';
document.querySelector('.lg\\:col-span-2').appendChild(monthlyCtx);

// Sample data - In a real implementation, fetch this from PHP
const monthlyData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{
        label: 'Monthly Spending',
        data: [650, 590, 800, 810, 560, <?= $current_spend ?>],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
    }]
};

new Chart(monthlyCtx, {
    type: 'line',
    data: monthlyData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Monthly Spending Trend'
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
  const trigger = document.getElementById('profileTrigger');
  const menu = document.getElementById('profileMenu');
  let timeoutId;

  // Show menu on hover
  trigger.addEventListener('mouseenter', () => {
    clearTimeout(timeoutId);
    menu.classList.remove('invisible', 'opacity-0');
    menu.classList.add('visible', 'opacity-100');
  });

  // Hide menu with delay when leaving trigger
  trigger.addEventListener('mouseleave', () => {
    timeoutId = setTimeout(() => {
      if (!menu.matches(':hover')) {
        menu.classList.add('invisible', 'opacity-0');
        menu.classList.remove('visible', 'opacity-100');
      }
    }, 100);
  });

  // Keep menu open when hovering over it
  menu.addEventListener('mouseenter', () => {
    clearTimeout(timeoutId);
    menu.classList.remove('invisible', 'opacity-0');
    menu.classList.add('visible', 'opacity-100');
  });

  // Hide menu when leaving it
  menu.addEventListener('mouseleave', () => {
    timeoutId = setTimeout(() => {
      menu.classList.add('invisible', 'opacity-0');
      menu.classList.remove('visible', 'opacity-100');
    }, 100);
  });
});
</script>

 <!--   <a href="settings.php" class="px-4 py-2 rounded-xl flex items-center justify-center">
    <span class="material-symbols-rounded">settings</span>
  </a>-->
<form method="POST" action="logout.php">
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 flex items-center justify-center">
      <span class="material-symbols-rounded">logout</span>
    </button>
  </form>
  

</div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-100 p-5 rounded-xl shadow">
          <h3 class="text-lg font-semibold text-blue-800">Monthly Spend</h3>
          <p class="text-2xl font-bold text-blue-900 mt-2">
            <?= $currency ?> <?= number_format($current_spend, 2) ?>
          </p>
        </div>
        <div class="bg-green-100 p-5 rounded-xl shadow">
          <h3 class="text-lg font-semibold text-green-800">Monthly Budget</h3>
          <p class="text-2xl font-bold text-green-900 mt-2">
            <?= $currency ?> <?= number_format($monthly_budget, 2) ?>
          </p>
        </div>
      </div>

      <!-- Budget Progress -->
<div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-xl font-semibold mb-4">Budget Progress</h3>
        <div class="space-y-4">
          <?php foreach ($budgets as $budget): ?>
            <?php 
              $progress = $budget['amount'] > 0 ? ($budget['spent'] / $budget['amount']) * 100 : 0;
              // UPDATED COLOR THRESHOLDS
              $progress_color = $progress >= 80 ? 'bg-red-500' : 
                               ($progress > 75 ? 'bg-yellow-500' : 'bg-blue-500');
            ?>
            <div>
              <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">
                  <?= htmlspecialchars($budget['category_name'] ?? 'No Category') ?>
                </span>
                <span class="text-sm font-medium">
                  <?= number_format($budget['spent'], 2) ?>/<?= number_format($budget['amount'], 2) ?>
                </span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full <?= $progress_color ?>" 
                     style="width: <?= min($progress, 100) ?>%"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-xl font-semibold mb-4">Recent Transactions</h3>
        <div class="space-y-4">
          <?php foreach ($transactions as $transaction): ?>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
              <div>
                <p class="font-medium">
                  <?= htmlspecialchars($transaction['description']) ?>
                </p>
                <p class="text-sm text-gray-500">
                  <?= htmlspecialchars($transaction['category_name']) ?> • 
                  <?= date('M j, Y', strtotime($transaction['transaction_date'])) ?>
                </p>
              </div>
              <span class="<?= $transaction['transaction_type'] === 'income' ? 'text-green-600' : 'text-red-600' ?> font-semibold">
                <?= $transaction['transaction_type'] === 'income' ? '+' : '-' ?>
                <?= $currency ?> <?= number_format($transaction['amount'], 2) ?>
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-6">
      <!-- Notifications -->
      <!--<div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-xl font-semibold mb-4">Notifications</h3>
        <div class="space-y-3">
          <?php foreach ($notifications as $notification): ?>
            <div class="p-3 bg-blue-50 rounded-lg">
              <p class="text-sm text-gray-800">
                <?= htmlspecialchars($notification['message']) ?>
              </p>
              <p class="text-xs text-gray-500 mt-1">
                <?= date('M j, g:i a', strtotime($notification['created_at'])) ?>
              </p>
            </div>
          <?php endforeach; ?>
          <?php if (empty($notifications)): ?>
            <p class="text-gray-500 text-sm">No new notifications</p>
          <?php endif; ?>
        </div>
      </div>-->

      <!-- Financial Goals -->
      <!--<div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-xl font-semibold mb-4">Financial Goals</h3>
        <div class="space-y-4">
          <?php foreach ($goals as $goal): ?>
            <?php 
              $goal_progress = $goal['target_amount'] > 0 ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
            ?>
            <div>
              <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">
                  <?= htmlspecialchars($goal['name']) ?>
                </span>
                <span class="text-sm font-medium">
                  <?= number_format($goal['current_amount'], 2) ?>/<?= number_format($goal['target_amount'], 2) ?>
                </span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full bg-green-500" 
                     style="width: <?= min($goal_progress, 100) ?>%"></div>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if (empty($goals)): ?>
            <p class="text-gray-500 text-sm">No active goals</p>
          <?php endif; ?>
        </div>
      </div>
-->
      <!-- Spending Chart -->
      <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-xl font-semibold mb-4">Spending by Category</h3>
        <canvas id="categoryChart" class="w-full"></canvas>
      </div>
    </div>
  </div>

<script>
// Category Spending Chart
const ctx = document.getElementById('categoryChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($categories, 'name')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($categories, 'total')) ?>,
            backgroundColor: <?= json_encode(array_map(function($cat) { 
                return $cat['color'] ?? '#999999'; 
            }, $categories)) ?>,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#000'
                }
            }
        }
    }
});
</script>
<div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50">
  <div class="flex flex-row justify-center gap-4 items-center px-4">


    <!-- Add Budget (Green Center Button) -->
    <a href="add-budget.php" 
       class="bg-green-500 hover:bg-green-600 text-white p-4 md:px-6 md:py-3 rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center gap-2 group">
      <span class="material-symbols-rounded">savings</span>
      <span class="hidden md:inline">Set Budget</span>
    </a>
    <!-- Add Expense -->
    <a href="add-expense.php" 
       class="bg-blue-600 hover:bg-blue-700 text-white p-4 md:px-6 md:py-3 rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center gap-2 group">
      <span class="material-symbols-rounded">add</span>
      <span class="hidden md:inline">Add Expense</span>
    </a>
    <!-- Quick Report -->
    <a href="reports.php" 
       class="bg-purple-600 hover:bg-purple-700 text-white p-4 md:px-6 md:py-3 rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center gap-2 group">
      <span class="material-symbols-rounded">bar_chart</span>
      <span class="hidden md:inline">Reports</span>
    </a>
  </div>
</div>
</body>
</html>
