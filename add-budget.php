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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (float)$_POST['amount'];
    $period = $_POST['period'];
    $start_date = $_POST['start_date'];
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';

    if ($amount <= 0) {
        $error = "Amount must be greater than 0";
    } elseif (!in_array($period, ['daily', 'weekly', 'monthly', 'yearly'])) {
        $error = "Invalid budget period";
    } elseif (empty($category_id)) {
        $error = "Budget category is required";
    } else {
        $stmt = $conn->prepare("INSERT INTO budgets (user_id, amount, period, start_date, end_date, category_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idsssi", $user_id, $amount, $period, $start_date, $end_date, $category_id);
        if ($stmt->execute()) {
            header("Location: list-budgets.php?success=Budget+created");
            exit();
        } else {
            $error = "Error creating budget: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Budget - SpendWise</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary: #0c4a6e;
            --primary-light: #0369a1;
            --secondary: #15803d;
            --accent: #f59e0b;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e2e8f0 100%);
        }
        
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(31, 38, 135, 0.2);
        }
        
        .input-focus:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(6, 105, 161, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(6, 105, 161, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, #16a34a 100%);
        }
        
        .suggestions-container {
            position: absolute;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .suggestion-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .suggestion-item:hover {
            background-color: #f3f4f6;
            transform: translateX(5px);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        .progress-bar {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            width: 0%;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .period-btn {
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        
        .period-btn.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(6, 105, 161, 0.2);
        }
        
        .selected-category {
            background: linear-gradient(90deg, rgba(12, 74, 110, 0.1) 0%, rgba(21, 128, 61, 0.1) 100%);
            border-left: 4px solid var(--secondary);
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8 animate__animated animate__fadeIn">
            <div class="w-20 h-20 bg-white rounded-2xl shadow-md flex items-center justify-center mx-auto mb-4">
                <div class="w-16 h-16 hero-gradient rounded-full flex items-center justify-center floating">
                    <i class="fas fa-piggy-bank text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Create New Budget</h1>
            <p class="text-gray-600 mt-2">Plan your finances wisely</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg flex items-start animate__animated animate__shakeX">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <div class="card p-8 animate__animated animate__fadeInUp">
            <form method="POST" class="space-y-6" id="budgetForm">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-tag text-gray-400 mr-2"></i>
                        Budget Category
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="categoryInput" autocomplete="off" 
                               class="input-focus pl-10 w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#0c4a6e]"
                               placeholder="Search categories...">
                        <input type="hidden" name="category_id" id="categoryId">
                        <div id="suggestionsContainer" class="suggestions-container hidden mt-2"></div>
                    </div>
                    <div id="selectedCategory" class="mt-3 p-3 selected-category rounded-lg hidden flex items-center">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <span id="selectedCategoryText" class="font-medium text-gray-800"></span>
                        <button type="button" onclick="clearCategory()" class="ml-auto text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-dollar-sign text-gray-400 mr-2"></i>
                        Amount
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <span>$</span>
                        </div>
                        <input type="number" step="0.01" name="amount" required 
                               class="input-focus pl-10 w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#0c4a6e]"
                               placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                        Budget Period
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" data-period="daily" class="period-btn border border-gray-200 rounded-xl py-3 text-sm transition">
                            <i class="far fa-sun mr-1"></i> Daily
                        </button>
                        <button type="button" data-period="weekly" class="period-btn border border-gray-200 rounded-xl py-3 text-sm transition">
                            <i class="far fa-calendar-alt mr-1"></i> Weekly
                        </button>
                        <button type="button" data-period="monthly" class="period-btn active border border-gray-200 rounded-xl py-3 text-sm transition">
                            <i class="far fa-calendar mr-1"></i> Monthly
                        </button>
                        <button type="button" data-period="yearly" class="period-btn border border-gray-200 rounded-xl py-3 text-sm transition">
                            <i class="far fa-star mr-1"></i> Yearly
                        </button>
                    </div>
                    <input type="hidden" name="period" id="periodInput" value="monthly">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="far fa-calendar-plus text-gray-400 mr-2"></i>
                            Start Date
                        </label>
                        <input type="date" name="start_date" required 
                               class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#0c4a6e]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="far fa-calendar-minus text-gray-400 mr-2"></i>
                            End Date <span class="text-xs text-gray-500 ml-1">(optional)</span>
                        </label>
                        <input type="date" name="end_date" 
                               class="input-focus w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#0c4a6e]">
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">Setup Progress</span>
                        <span id="progressPercentage" class="text-sm font-medium text-[#0c4a6e]">0%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="formProgress"></div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="list-budgets.php" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="btn-primary px-6 py-3 text-white rounded-xl hover:shadow-md transition flex items-center">
                        <i class="fas fa-plus mr-2"></i> Create Budget
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        // Category search functionality
        const categoryInput = document.getElementById('categoryInput');
        const suggestionsContainer = document.getElementById('suggestionsContainer');
        const categoryIdInput = document.getElementById('categoryId');
        const selectedCategoryDiv = document.getElementById('selectedCategory');
        const selectedCategoryText = document.getElementById('selectedCategoryText');
        
        // Period selection
        const periodBtns = document.querySelectorAll('.period-btn');
        const periodInput = document.getElementById('periodInput');
        
        // Form progress
        const formProgress = document.getElementById('formProgress');
        const progressPercentage = document.getElementById('progressPercentage');
        const form = document.getElementById('budgetForm');
        const submitBtn = document.getElementById('submitBtn');

        // Category search
        categoryInput.addEventListener('input', async function(e) {
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                suggestionsContainer.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`get_categories.php?query=${encodeURIComponent(query)}`);
                const categories = await response.json();
                
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.remove('hidden');
                
                if (categories.length === 0) {
                    suggestionsContainer.innerHTML = `
                        <div class="suggestion-item text-gray-500 p-3 text-center">
                            <i class="far fa-frown mr-2"></i> No matching categories found
                        </div>
                    `;
                    return;
                }

                categories.forEach(category => {
                    const div = document.createElement('div');
                    div.className = 'suggestion-item';
                    div.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-tag text-[#0c4a6e] mr-3"></i>
                            <span>${category.name}</span>
                        </div>
                    `;
                    div.addEventListener('click', () => {
                        categoryInput.value = category.name;
                        categoryIdInput.value = category.category_id;
                        suggestionsContainer.classList.add('hidden');
                        selectedCategoryDiv.classList.remove('hidden');
                        selectedCategoryText.textContent = category.name;
                        updateFormProgress();
                        
                        // Add animation
                        selectedCategoryDiv.classList.add('animate__animated', 'animate__pulse');
                        setTimeout(() => {
                            selectedCategoryDiv.classList.remove('animate__animated', 'animate__pulse');
                        }, 1000);
                    });
                    suggestionsContainer.appendChild(div);
                });
            } catch (error) {
                console.error('Error fetching suggestions:', error);
                suggestionsContainer.innerHTML = `
                    <div class="suggestion-item text-red-500 p-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Error loading categories
                    </div>
                `;
                suggestionsContainer.classList.remove('hidden');
            }
        });

        function clearCategory() {
            categoryInput.value = '';
            categoryIdInput.value = '';
            selectedCategoryDiv.classList.add('hidden');
            updateFormProgress();
        }

        // Period selection
        periodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                periodBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                periodInput.value = this.dataset.period;
            });
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#categoryInput') && !e.target.closest('#suggestionsContainer')) {
                suggestionsContainer.classList.add('hidden');
            }
        });

        // Form progress tracking
        function updateFormProgress() {
            const inputs = Array.from(form.elements).filter(el => 
                el.tagName === 'INPUT' || el.tagName === 'SELECT'
            );
            const filled = inputs.filter(el => 
                el.value && el.type !== 'hidden' || 
                (el.type === 'hidden' && el.id === 'categoryId' && el.value)
            ).length;
            
            const total = inputs.length - 1; // exclude hidden period input
            const percentage = Math.round((filled / total) * 100);
            formProgress.style.width = `${percentage}%`;
            progressPercentage.textContent = `${percentage}%`;
            
            if (percentage === 100) {
                submitBtn.classList.add('animate__animated', 'animate__pulse');
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Ready to Create';
            } else {
                submitBtn.classList.remove('animate__animated', 'animate__pulse');
                submitBtn.innerHTML = '<i class="fas fa-plus mr-2"></i> Create Budget';
            }
        }

        // Update progress on any input change
        form.addEventListener('input', updateFormProgress);
        form.addEventListener('change', updateFormProgress);

        // Initialize progress
        updateFormProgress();

        // Set default start date to today
        document.querySelector('input[name="start_date"]').valueAsDate = new Date();
    </script>
</body>
</html>