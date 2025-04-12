<?php
session_start();
include __DIR__ . '/config.php';
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wise - Smart Personal Finance Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --primary: #0c4a6e;
            --primary-light: #0369a1;
            --secondary: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
            --text-dark: #1e293b; /* Added new text color */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            overflow-x: hidden;
            color: var(--text-dark); /* Set default text color */
        }

        .hero-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Animation Classes */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Greeting Animation */
        .greeting-container {
            position: relative;
            height: 60px;
            overflow: hidden;
            display: inline-block;
        }
        
        .greeting-text {
            position: absolute;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
            left: 0;
            width: 100%;
        }
        
        .greeting-text.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        @keyframes wave {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .wave {
            display: inline-block;
            animation: wave 1.2s infinite;
        }

        /* Pulse Animation for CTA */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .pulse:hover {
            animation: pulse 1.5s infinite;
        }

        /* Glow Animation */
        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(16, 185, 129, 0.5); }
            50% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.8); }
            100% { box-shadow: 0 0 5px rgba(16, 185, 129, 0.5); }
        }

        .glow-card {
            animation: glow 3s infinite;
        }

        /* Hover Grow Effect */
        .hover-grow {
            transition: all 0.3s ease;
        }
        
        .hover-grow:hover {
            transform: scale(1.03);
        }

        /* Feature Icon Animation */
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover .feature-icon {
            transform: rotate(10deg) scale(1.1);
        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 60px;
            border: 2px solid var(--text-dark);
            border-radius: 25px;
            opacity: 0.7;
            z-index: 100;
        }
        
        .scroll-indicator:before {
            content: '';
            position: absolute;
            top: 10px;
            left: 50%;
            width: 8px;
            height: 8px;
            background: var(--text-dark);
            border-radius: 50%;
            transform: translateX(-50%);
            animation: scrollBounce 2s infinite;
        }
        
        @keyframes scrollBounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
            40% { transform: translateY(20px) translateX(-50%); }
            60% { transform: translateY(10px) translateX(-50%); }
        }

        /* Tooltip */
        .tooltip {
            position: relative;
        }
        
        .tooltip .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Scroll Indicator -->
    <div class="scroll-indicator animate__animated animate__fadeIn animate__delay-1s hidden md:block"></div>

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-wallet text-[#0c4a6e] text-2xl mr-2 animate__animated animate__pulse animate__infinite animate__slower"></i>
                        <span class="text-xl font-bold text-[#0c4a6e]">Spend<span class="text-green-600">Wise</span></span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-[#0c4a6e] transition duration-300 group">
                        Features
                        <span class="block h-0.5 bg-[#0c4a6e] w-0 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-[#0c4a6e] transition duration-300 group">
                        How It Works
                        <span class="block h-0.5 bg-[#0c4a6e] w-0 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <?php if ($isLoggedIn): ?>
                        <a href="dashboard.php" class="bg-[#0c4a6e] text-white px-4 py-2 rounded-lg hover:bg-[#075985] transition duration-300 transform hover:scale-105 flex items-center">
                            Dashboard <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="text-[#0c4a6e] hover:text-[#075985] transition duration-300 flex items-center">
                            Login <i class="fas fa-sign-in-alt ml-2"></i>
                        </a>
                        <a href="register.php" class="bg-[#0c4a6e] text-white px-4 py-2 rounded-lg hover:bg-[#075985] transition duration-300 transform hover:scale-105 flex items-center">
                            Sign Up Free <i class="fas fa-user-plus ml-2"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-[#1e293b] py-20 md:py-28 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute top-20 left-10 w-16 h-16 rounded-full bg-white opacity-10 animate__animated animate__pulse animate__infinite animate__slow"></div>
            <div class="absolute bottom-10 right-20 w-24 h-24 rounded-full bg-white opacity-10 animate__animated animate__pulse animate__infinite animate__slower"></div>
            <div class="absolute top-1/3 right-1/4 w-12 h-12 rounded-full bg-white opacity-10 animate__animated animate__pulse animate__infinite animate__slow"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 animate__animated animate__fadeInLeft">
                    <!-- Animated Greeting -->
                    <div class="mb-6">
                        <div class="greeting-container">
                            <p class="greeting-text active text-2xl font-medium">Welcome to Wise!</p>
                            <p class="greeting-text text-2xl font-medium">Bienvenue sur Wise!</p>
                            <p class="greeting-text text-2xl font-medium">Willkommen bei Wise!</p>
                            <p class="greeting-text text-2xl font-medium">¡Bienvenido a Wise!</p>
                        </div>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight animate__animated animate__fadeIn animate__delay-1s">Take Control of Your <span class="text-amber-300">Finances</span></h1>
                    <p class="text-lg mb-8 opacity-90 max-w-lg animate__animated animate__fadeIn animate__delay-1s">Wise helps you track expenses, save money, and achieve financial freedom with smart budgeting tools.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 animate__animated animate__fadeIn animate__delay-1s">
                        <?php if ($isLoggedIn): ?>
                            <a href="dashboard.php" class="bg-white text-[#0c4a6e] px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 transform hover:scale-105 text-center pulse flex items-center justify-center">
                                Go to Dashboard <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        <?php else: ?>
                            <a href="register.php" class="bg-white text-[#0c4a6e] px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 transform hover:scale-105 text-center pulse flex items-center justify-center">
                                Get Started Free <i class="fas fa-rocket ml-2"></i>
                            </a>
                            <a href="#features" class="bg-transparent border-2 border-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-[#0c4a6e] transition duration-300 text-center flex items-center justify-center">
                                Learn More <i class="fas fa-info-circle ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center animate__animated animate__fadeInRight animate__delay-1s">
                    <div class="relative">
                        <img src="https://illustrations.popsy.co/amber/digital-nomad.svg" alt="Finance Illustration" class="w-full max-w-md floating">
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-green-400 rounded-full opacity-20 animate__animated animate__pulse animate__infinite animate__slower"></div>
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-blue-400 rounded-full opacity-20 animate__animated animate__pulse animate__infinite animate__slow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate__animated animate__fadeIn">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Everything you need to manage your money effectively</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl card-hover animate__animated animate__fadeInUp animate__delay-1s hover-grow">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-chart-pie text-indigo-600 text-2xl feature-icon"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Budgeting" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-bold mb-3 text-center">Smart Budgeting</h3>
                    <p class="text-gray-600 text-center">Create custom budgets and get real-time spending alerts to stay on track.</p>
                    <div class="mt-4 text-center">
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
                            Learn more <i class="fas fa-chevron-right ml-1 text-sm"></i>
                        </a>
                    </div>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl card-hover animate__animated animate__fadeInUp animate__delay-2s hover-grow glow-card">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-exchange-alt text-green-600 text-2xl feature-icon"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Expense Tracking" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-bold mb-3 text-center">Expense Tracking</h3>
                    <p class="text-gray-600 text-center">Automatically categorize transactions and visualize your spending habits.</p>
                    <div class="mt-4 text-center">
                        <a href="#" class="text-green-600 hover:text-green-800 font-medium inline-flex items-center">
                            Learn more <i class="fas fa-chevron-right ml-1 text-sm"></i>
                        </a>
                    </div>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl card-hover animate__animated animate__fadeInUp animate__delay-3s hover-grow">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-bell text-blue-600 text-2xl feature-icon"></i>
                    </div>
                    <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Bill Reminders" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-bold mb-3 text-center">Bill Reminders</h3>
                    <p class="text-gray-600 text-center">Never miss a payment with automated reminders for upcoming bills.</p>
                    <div class="mt-4 text-center">
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                            Learn more <i class="fas fa-chevron-right ml-1 text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate__animated animate__fadeIn">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How Wise Works</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Simple steps to financial clarity</p>
            </div>
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 animate__animated animate__fadeInLeft">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="How It Works" class="w-full max-w-md mx-auto rounded-lg shadow-lg">
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-purple-400 rounded-full opacity-20 animate__animated animate__pulse animate__infinite animate__slower"></div>
                    </div>
                </div>
                <div class="md:w-1/2 animate__animated animate__fadeInRight">
                    <div class="space-y-8">
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-[#0c4a6e] text-white font-bold shadow-md group-hover:bg-[#10b981] transition duration-300">1</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#0c4a6e] transition duration-300">Sign Up in Seconds</h3>
                                <p class="mt-2 text-gray-600 group-hover:text-gray-800 transition duration-300">Create your free account with just an email and password.</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-[#0c4a6e] text-white font-bold shadow-md group-hover:bg-[#10b981] transition duration-300">2</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#0c4a6e] transition duration-300">Connect Your Accounts</h3>
                                <p class="mt-2 text-gray-600 group-hover:text-gray-800 transition duration-300">Securely link your bank accounts or enter transactions manually.</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-[#0c4a6e] text-white font-bold shadow-md group-hover:bg-[#10b981] transition duration-300">3</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#0c4a6e] transition duration-300">Gain Financial Insight</h3>
                                <p class="mt-2 text-gray-600 group-hover:text-gray-800 transition duration-300">Get personalized reports and recommendations to improve your finances.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 animate__animated animate__fadeIn animate__delay-1s">
                        <a href="register.php" class="bg-[#0c4a6e] text-white px-6 py-3 rounded-lg font-medium hover:bg-[#075985] transition duration-300 transform hover:scale-105 inline-flex items-center">
                            Start Your Journey <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient text-[#1e293b] relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-16 h-16 rounded-full bg-white opacity-10 animate__animated animate__pulse animate__infinite animate__slow"></div>
            <div class="absolute bottom-1/3 right-1/3 w-24 h-24 rounded-full bg-white opacity-10 animate__animated animate__pulse animate__infinite animate__slower"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <h2 class="text-3xl font-bold mb-6 animate__animated animate__fadeIn">Ready to Transform Your Finances?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto opacity-90 animate__animated animate__fadeIn animate__delay-1s">Join thousands of users who are taking control of their money with Wise.</p>
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php" class="bg-white text-[#0c4a6e] px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105 inline-block pulse animate__animated animate__fadeIn animate__delay-2s flex items-center justify-center mx-auto">
                    Go to Dashboard <i class="fas fa-arrow-right ml-2"></i>
                </a>
            <?php else: ?>
                <a href="register.php" class="bg-white text-[#0c4a6e] px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105 inline-block pulse animate__animated animate__fadeIn animate__delay-2s flex items-center justify-center mx-auto">
                    Get Started Free <i class="fas fa-rocket ml-2"></i>
                </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate__animated animate__fadeIn">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Find answers to common questions about Wise</p>
            </div>
            
            <div class="max-w-3xl mx-auto space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition duration-300 animate__animated animate__fadeIn animate__delay-1s">
                    <button class="flex justify-between items-center w-full text-left group" onclick="toggleFAQ(1)">
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-[#0c4a6e] transition duration-300">Is Wise really free to use?</h3>
                        <i class="fas fa-chevron-down text-gray-500 group-hover:text-[#0c4a6e] transition duration-300 transform group-focus:-rotate-180"></i>
                    </button>
                    <div id="faq-answer-1" class="mt-2 text-gray-600 hidden">
                        <p>Yes! Wise offers a completely free plan with all the essential features you need to manage your finances. We also offer a premium plan with additional features for power users.</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition duration-300 animate__animated animate__fadeIn animate__delay-2s">
                    <button class="flex justify-between items-center w-full text-left group" onclick="toggleFAQ(2)">
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-[#0c4a6e] transition duration-300">How secure is my financial data?</h3>
                        <i class="fas fa-chevron-down text-gray-500 group-hover:text-[#0c4a6e] transition duration-300"></i>
                    </button>
                    <div id="faq-answer-2" class="mt-2 text-gray-600 hidden">
                        <p>Security is our top priority. We use bank-level 256-bit encryption to protect your data, and we never store your banking credentials on our servers.</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition duration-300 animate__animated animate__fadeIn animate__delay-3s">
                    <button class="flex justify-between items-center w-full text-left group" onclick="toggleFAQ(3)">
                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-[#0c4a6e] transition duration-300">Can I use Wise on my mobile device?</h3>
                        <i class="fas fa-chevron-down text-gray-500 group-hover:text-[#0c4a6e] transition duration-300"></i>
                    </button>
                    <div id="faq-answer-3" class="mt-2 text-gray-600 hidden">
                        <p>Absolutely! Wise is fully responsive and works great on all devices. We also offer native iOS and Android apps for the best mobile experience.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-wallet text-indigo-400 text-2xl mr-2 animate__animated animate__pulse animate__infinite animate__slower"></i>
                        <span class="text-xl font-bold">Spend<span class="text-green-600">Wise</span></span>
                    </div>
                    <p class="text-gray-400">Smart personal finance management for everyone.</p>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 tooltip">
                            <i class="fab fa-twitter text-xl"></i>
                            <span class="tooltip-text">Twitter</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 tooltip">
                            <i class="fab fa-facebook text-xl"></i>
                            <span class="tooltip-text">Facebook</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 tooltip">
                            <i class="fab fa-instagram text-xl"></i>
                            <span class="tooltip-text">Instagram</span>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 tooltip">
                            <i class="fab fa-linkedin text-xl"></i>
                            <span class="tooltip-text">LinkedIn</span>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Product</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition duration-300">Features</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition duration-300">How It Works</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Security</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Wise. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Greeting text animation
        const greetingTexts = document.querySelectorAll('.greeting-text');
        let currentGreeting = 0;

        function rotateGreeting() {
            greetingTexts[currentGreeting].classList.remove('active');
            currentGreeting = (currentGreeting + 1) % greetingTexts.length;
            greetingTexts[currentGreeting].classList.add('active');
        }

        // Start the greeting rotation
        setInterval(rotateGreeting, 3000);

        // FAQ toggle function
        function toggleFAQ(id) {
            const answer = document.getElementById(`faq-answer-${id}`);
            const icon = document.querySelector(`#faq-answer-${id}`).previousElementSibling.querySelector('i');
            
            answer.classList.toggle('hidden');
            icon.classList.toggle('transform');
            icon.classList.toggle('rotate-180');
        }

        // Scroll indicator animation
        window.addEventListener('scroll', function() {
            const scrollIndicator = document.querySelector('.scroll-indicator');
            if (window.scrollY > 100) {
                scrollIndicator.classList.add('opacity-0');
                scrollIndicator.classList.remove('opacity-70');
            } else {
                scrollIndicator.classList.remove('opacity-0');
                scrollIndicator.classList.add('opacity-70');
            }
        });
    </script>
</body>
</html>