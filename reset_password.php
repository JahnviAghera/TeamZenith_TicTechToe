<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - SPendWise</title>
  <meta name="theme-color" content="#0c4a6e">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .gradient-bg {
      background: linear-gradient(135deg, #075985 0%, #0c4a6e 100%);
    }
    .input-focus:focus {
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
    }
    .transition-all {
      transition: all 0.3s ease;
    }
    .illustration-container {
      background-color: #f8fafc;
      border-radius: 0.5rem;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }
    .finance-illustration {
      max-width: 100%;
      height: auto;
      border-radius: 0.25rem;
    }
  </style>
</head>
<body class="gradient-bg flex items-center justify-center min-h-screen p-4">
  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all transform hover:shadow-2xl">
      <div class="p-8">
        <!-- Finance Illustration Section -->
        <div class="illustration-container text-center mb-6">
          <img src="WhatsApp Image 2025-04-12 at 11.39.11_e9b919d4.jpg" 
               alt="Tax Withholding and Estimated Tax Illustration" 
               class="finance-illustration mx-auto">
          <p class="text-xs text-gray-500 mt-2">Finance Security Illustration</p>
        </div>
        
        <div class="flex justify-center mb-6">
          <div class="bg-blue-100 p-3 rounded-full">
            <i class="fas fa-key text-blue-600 text-3xl"></i>
          </div>
        </div>
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Reset Password</h2>
        <p class="text-center text-gray-600 mb-8">Enter your email to receive a reset link</p>
        
        <?php if (!empty($error)): ?>
          <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700"><?= htmlspecialchars($error) ?></p>
              </div>
            </div>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
          <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm text-green-700"><?= htmlspecialchars($success) ?></p>
              </div>
            </div>
          </div>
        <?php endif; ?>
        
        <form method="POST">
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
              </div>
              <input type="email" id="email" name="email" required
                     class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 transition-all"
                     placeholder="Your registered email address">
            </div>
          </div>
          
          <button type="submit"
                  class="w-full gradient-bg text-white py-3 px-4 rounded-lg font-medium hover:opacity-90 transition-all flex items-center justify-center">
            <i class="fas fa-paper-plane mr-2"></i> Send Reset Link
          </button>
        </form>

        <div class="mt-8 border-t border-gray-200 pt-6 text-center">
          <p class="text-sm text-gray-600">
            Remember your password? 
            <a href="login.php" class="font-medium text-blue-600 hover:underline">Sign in</a>
          </p>
        </div>
      </div>
    </div>

    <div class="mt-6 text-center text-white text-sm">
      <p>&copy; <?= date('Y') ?> SPendWise. All rights reserved.</p>
    </div>
  </div>
</body>
</html>