<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Expense - SpendWise</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    .float-animation {
      animation: float 3s ease-in-out infinite;
    }
    .category-icon {
      transition: all 0.3s ease;
    }
    .category-icon:hover {
      transform: scale(1.2) rotate(10deg);
    }
    .input-focus-effect:focus {
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
    }
    .pulse-on-hover:hover {
      animation: pulse 1.5s infinite;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen p-4 pb-24">
  <!-- Floating coins decoration -->
  <div class="fixed top-10 left-10 opacity-20 float-animation">
    <span class="material-symbols-rounded text-yellow-400 text-4xl">monetization_on</span>
  </div>
  <div class="fixed bottom-20 right-10 opacity-20 float-animation" style="animation-delay: 0.5s">
    <span class="material-symbols-rounded text-green-400 text-4xl">savings</span>
  </div>
  <div class="fixed top-1/3 right-1/4 opacity-20 float-animation" style="animation-delay: 1s">
    <span class="material-symbols-rounded text-red-400 text-4xl">receipt</span>
  </div>

  <!-- Header -->
  <div class="flex justify-between items-center mb-6 animate__animated animate__fadeInDown">
    <div>
      <h1 class="text-3xl font-bold text-indigo-800">Add New Expense</h1>
      <p class="text-indigo-600">Track your spending effortlessly</p>
    </div>
    <div class="relative group" id="profileDropdown">
      <div class="w-10 h-10 rounded-full bg-indigo-200 overflow-hidden flex items-center justify-center cursor-pointer shadow-md hover:shadow-lg transition-shadow" id="profileTrigger">
        <?php if ($profile_img): ?>
          <img src="<?= htmlspecialchars($profile_img) ?>" alt="Profile" class="w-full h-full object-cover">
        <?php else: ?>
          <span class="text-xl font-semibold text-indigo-700"><?= strtoupper(substr($first_name, 0, 1)) ?></span>
        <?php endif; ?>
      </div>
      <!-- Dropdown Menu -->
      <div class="absolute right-0 mt-2 w-48 bg-white border border-indigo-100 rounded-lg shadow-xl opacity-0 invisible transition-all duration-300 z-50" 
           id="profileMenu">
        <a href="profile.php" class="block px-4 py-2 text-indigo-700 hover:bg-indigo-50 transition-colors flex items-center gap-2">
          <span class="material-symbols-rounded text-indigo-500">person</span>
          Profile
        </a>
        <a href="settings.php" class="block px-4 py-2 text-indigo-700 hover:bg-indigo-50 transition-colors flex items-center gap-2">
          <span class="material-symbols-rounded text-indigo-500">settings</span>
          Settings
        </a>
        <form method="POST" action="logout.php">
          <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
            <span class="material-symbols-rounded">logout</span>
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="max-w-2xl mx-auto animate__animated animate__fadeInUp">
    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2 animate__animated animate__headShake">
        <span class="material-symbols-rounded text-red-500">error</span>
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded-2xl shadow-xl space-y-6 border border-indigo-50 hover:shadow-2xl transition-shadow duration-300">
      <div class="space-y-4">
        <div>
          <label for="description" class="block text-sm font-medium text-indigo-700 mb-2 flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-500 category-icon">description</span>
            Description*
          </label>
          <input type="text" name="description" id="description" required 
                class="w-full px-4 py-2.5 rounded-lg border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all input-focus-effect bg-indigo-50"
                value="<?= htmlspecialchars($_POST['description'] ?? '') ?>">
        </div>

        <div>
          <label for="amount" class="block text-sm font-medium text-indigo-700 mb-2 flex items-center gap-2">
            <span class="material-symbols-rounded text-green-500 category-icon">attach_money</span>
            Amount*
          </label>
          <input type="number" step="0.01" name="amount" id="amount" required
                class="w-full px-4 py-2.5 rounded-lg border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all input-focus-effect bg-indigo-50"
                value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>">
        </div>

        <div>
          <label for="category_id" class="block text-sm font-medium text-indigo-700 mb-2 flex items-center gap-2">
            <span class="material-symbols-rounded text-purple-500 category-icon">category</span>
            Category*
          </label>
          <select name="category_id" id="category_id" required 
                class="w-full px-4 py-2.5 rounded-lg border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all input-focus-effect bg-indigo-50">
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['category_id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="payment_method" class="block text-sm font-medium text-indigo-700 mb-2 flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-500 category-icon">credit_card</span>
            Payment Method*
          </label>
          <select name="payment_method" id="payment_method" required 
                class="w-full px-4 py-2.5 rounded-lg border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all input-focus-effect bg-indigo-50">
            <?php foreach ($payment_methods as $method): ?>
              <option value="<?= htmlspecialchars($method['name']) ?>" <?= ($_POST['payment_method'] ?? '') == $method['name'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($method['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <a href="dashboard.php" class="px-6 py-2.5 rounded-lg border border-indigo-200 hover:bg-indigo-50 transition-colors text-indigo-700 hover:text-indigo-900 flex items-center gap-2 pulse-on-hover">
          <span class="material-symbols-rounded">close</span>
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-gray-100 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-1">
          <span class="material-symbols-rounded">add</span>
          Add Expense
        </button>
      </div>
    </form>
  </div>

  <!-- Floating Action Button -->
  <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 animate__animated animate__bounceInUp">
    <div class="flex flex-row justify-center gap-4 items-center px-4">
      <a href="add-expense.php" 
         class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-gray-100 p-4 md:px-6 md:py-3 rounded-full shadow-xl transition-all transform hover:scale-110 flex items-center gap-2 group pulse-on-hover">
        <span class="material-symbols-rounded animate-spin">paid</span>
        <span class="hidden md:inline font-medium">Add Expense</span>
      </a>
    </div>
  </div>

  <script>
    // Profile Dropdown Script with animation
    document.addEventListener('DOMContentLoaded', () => {
      const trigger = document.getElementById('profileTrigger');
      const menu = document.getElementById('profileMenu');
      let timeoutId;

      trigger.addEventListener('mouseenter', () => {
        clearTimeout(timeoutId);
        menu.classList.remove('invisible', 'opacity-0');
        menu.classList.add('visible', 'opacity-100');
      });

      trigger.addEventListener('mouseleave', () => {
        timeoutId = setTimeout(() => {
          if (!menu.matches(':hover')) {
            menu.classList.add('invisible', 'opacity-0');
            menu.classList.remove('visible', 'opacity-100');
          }
        }, 100);
      });

      menu.addEventListener('mouseenter', () => {
        clearTimeout(timeoutId);
        menu.classList.remove('invisible', 'opacity-0');
        menu.classList.add('visible', 'opacity-100');
      });

      menu.addEventListener('mouseleave', () => {
        timeoutId = setTimeout(() => {
          menu.classList.add('invisible', 'opacity-0');
          menu.classList.remove('visible', 'opacity-100');
        }, 100);
      });

      // Add animation to form inputs on focus
      const inputs = document.querySelectorAll('input, select');
      inputs.forEach(input => {
        input.addEventListener('focus', () => {
          input.parentElement.classList.add('animate__animated', 'animate__pulse');
        });
        input.addEventListener('blur', () => {
          input.parentElement.classList.remove('animate__animated', 'animate__pulse');
        });
      });
    });
  </script>
</body>
</html>