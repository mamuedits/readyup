<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadyUp - Subscription Plans</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://pay.google.com/gp/p/js/pay.js"></script>
    <style>
        .gradient-text {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .hero-section {
            background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('back2.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        html {
            scroll-behavior: smooth;
        }
        .container-padding {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        @media (min-width: 1024px) {
            .container-padding {
                padding-left: 4rem;
                padding-right: 4rem;
            }
        }
        
        /* Preserve original animations and effects */
        .plan-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        
        .plan-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        .highlight-card {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .highlight-card::before {
            content: "MOST POPULAR";
            position: absolute;
            top: 15px;
            right: -40px;
            background: linear-gradient(45deg, #f59e0b, #ef4444);
            color: white;
            padding: 3px 40px;
            font-size: 12px;
            font-weight: 600;
            transform: rotate(45deg);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: -1;
        }
        
        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background-color: #3b82f6;
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 12px;
        }
        
        .glow-effect {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }
        
        .sliding-container {
            overflow: hidden;
        }
        
        .slide-in {
            transform: translateX(100%);
            opacity: 0;
        }
        
        /* Additional styles to match home page */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
 
        
        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .payment-container {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        #google-pay-button {
            width: 100%;
            height: 50px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Header -->
    <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto container-padding py-3 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index1.html" class="flex items-center">
                    <img src="Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                </a>
            </div>
            
            <nav class="hidden md:block">
                <ul class="flex space-x-8">
                    <li><a href="index1.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                    <li><a href="about1.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                    <li><a href="index1.html#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Events</a></li>
                    <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                </ul>
            </nav>
            
            <div class="flex items-center space-x-4">
                <a href="logout.php" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300 flex items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Logout
                </a>
                
                <div class="relative md:block flex items-center gap-5 group">
                    <input type="text" placeholder="   Search..." 
                        class="w-11 group-hover:w-64 transition-all duration-300 ease-in-out px-4 py-2 rounded-full border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500469 bg-gray-700 text-[#F5F7FA] focus:w-64">
                    <button class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 text-[#F5F7FA] group-hover:text-[#F5F7FA]">
                        <img src="search.png" alt="s" class="mx-0.5 w-full h-full">
                    </button>
                </div>
                <button class="md:hidden text-white text-2xl" id="mobileMenuButton">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu (hidden by default) -->
    <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
        <ul class="space-y-4">
            <li><a href="index.html" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
            <li><a href="index.html#about" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
            <li><a href="index.html#sectors" class="block text-white hover:text-blue-300 transition duration-300">Events</a></li>
            <li><a href="index.html#contact" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero-section flex items-center min-h-[50vh]">
        <div class="container mx-auto container-padding py-20 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                Choose Your <span class="gradient-text">Plan</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Simple pricing with powerful features to grow your startup events
            </p>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto container-padding">
            <!-- Billing toggle with animation -->
            <div class="flex justify-center items-center mb-12">
                <div class="bg-white p-1 rounded-full shadow-md inline-flex">
                    <button id="monthlyBtn" class="px-6 py-2 rounded-full font-medium bg-blue-600 text-white transition-all duration-300">
                        Monthly
                    </button>
                    <button id="annualBtn" class="px-6 py-2 rounded-full font-medium text-gray-600 hover:text-blue-600 transition-all duration-300">
                        Annual (Save 20%)
                    </button>
                </div>
            </div>
            
            <!-- Sliding Plans Container -->
            <div class="sliding-container relative overflow-hidden h-[600px] md:h-auto mb-24">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 opacity-0 slide-in" id="plans">
                    <!-- Free Plan -->
                    <div class="plan-card p-8 bg-white rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Starter</h3>
                            <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-600 rounded-full">FREE</span>
                        </div>
                        <p class="text-gray-500 mb-6">Perfect for individuals getting started</p>
                        
                        <div class="mb-8">
                            <span class="monthly-price text-3xl font-bold text-gray-800">₹0</span>
                            <span class="monthly-price text-gray-500">/month</span>
                            <span class="annual-price hidden text-3xl font-bold text-gray-800">₹0</span>
                            <span class="annual-price hidden text-gray-500">/year</span>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Browse all events
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Save favorites
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Event registration
                            </li>
                            <li class="flex items-center text-gray-400">
                                <span class="feature-icon bg-gray-400">✗</span> Create events
                            </li>
                            <li class="flex items-center text-gray-400">
                                <span class="feature-icon bg-gray-400">✗</span> Analytics
                            </li>
                        </ul>
                        
                        <button class="w-full py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors"
                                onclick="selectPlan('Starter')">
                            Current Plan
                        </button>
                    </div>
                    
                    <!-- Basic Plan -->
                    <div class="plan-card p-8 bg-white rounded-xl border border-blue-200 glow-effect">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Basic</h3>
                            <span class="text-xs font-semibold px-3 py-1 bg-blue-100 text-blue-600 rounded-full">POPULAR</span>
                        </div>
                        <p class="text-gray-500 mb-6">For individuals hosting small events</p>
                        
                        <div class="mb-8">
                            <span class="monthly-price text-3xl font-bold text-gray-800">₹900</span>
                            <span class="monthly-price text-gray-500">/month</span>
                            <span class="annual-price hidden text-3xl font-bold text-gray-800">₹700</span>
                            <span class="annual-price hidden text-gray-500">/month</span>
                            <div class="text-sm text-gray-500 mt-1">billed annually at ₹8400</div>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> All Starter features
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Create 5 events/month
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Basic analytics
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Email support
                            </li>
                            <li class="flex items-center text-gray-400">
                                <span class="feature-icon bg-gray-400">✗</span> Team members
                            </li>
                        </ul>
                        
                        <button class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors"
                                onclick="selectPlan('Basic')">
                            Get Started
                        </button>
                    </div>
                    
                    <!-- Pro Plan -->
                    <div class="plan-card highlight-card p-8 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold">Professional</h3>
                            <span class="text-xs font-semibold px-3 py-1 bg-white bg-opacity-20 rounded-full">BEST VALUE</span>
                        </div>
                        <p class="text-blue-100 mb-6">For growing businesses and teams</p>
                        
                        <div class="mb-8">
                            <span class="monthly-price text-3xl font-bold">₹2900</span>
                            <span class="monthly-price text-blue-100">/month</span>
                            <span class="annual-price hidden text-3xl font-bold">₹2300</span>
                            <span class="annual-price hidden text-blue-100">/month</span>
                            <div class="text-sm text-blue-100 mt-1">billed annually at ₹25,000</div>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center">
                                <span class="feature-icon bg-white text-blue-600">✓</span> All Basic features
                            </li>
                            <li class="flex items-center">
                                <span class="feature-icon bg-white text-blue-600">✓</span> Unlimited events
                            </li>
                            <li class="flex items-center">
                                <span class="feature-icon bg-white text-blue-600">✓</span> Advanced analytics
                            </li>
                            <li class="flex items-center">
                                <span class="feature-icon bg-white text-blue-600">✓</span> 3 team members
                            </li>
                            <li class="flex items-center">
                                <span class="feature-icon bg-white text-blue-600">✓</span> Priority support
                            </li>
                        </ul>
                        
                        <button class="w-full py-3 bg-white text-blue-600 rounded-lg font-medium hover:bg-gray-100 transition-colors"
                                onclick="selectPlan('Professional')">
                            Get Professional
                        </button>
                    </div>
                    
                    <!-- Enterprise Plan -->
                    <div class="plan-card p-8 bg-white rounded-xl border border-purple-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Enterprise</h3>
                            <span class="text-xs font-semibold px-3 py-1 bg-purple-100 text-purple-600 rounded-full">CUSTOM</span>
                        </div>
                        <p class="text-gray-500 mb-6">For large organizations</p>
                        
                        <div class="mb-8">
                            <span class="text-3xl font-bold text-gray-800">Custom</span>
                            <div class="text-sm text-gray-500 mt-1">Starting at ₹60,000/month</div>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> All Professional features
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Unlimited team members
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Dedicated account manager
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> Custom integrations
                            </li>
                            <li class="flex items-center text-gray-600">
                                <span class="feature-icon">✓</span> 24/7 premium support
                            </li>
                        </ul>
                        
                        <button class="w-full py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors"
                                onclick="selectPlan('Enterprise')">
                            Contact Sales
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Testimonials Section -->
            <div class="max-w-6xl mx-auto opacity-0 slide-in" id="testimonials">
                <h2 class="text-2xl font-bold text-center mb-12">Trusted by Thousands of Startups</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold mr-4">TS</div>
                            <div>
                                <h3 class="font-semibold">Taylor Smith</h3>
                                <p class="text-gray-500 text-sm">Founder, TechStart</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"The Professional plan gave us everything we needed to scale our events 3x in just 6 months."</p>
                        <div class="flex mt-3 text-yellow-400">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold mr-4">MJ</div>
                            <div>
                                <h3 class="font-semibold">Maria Johnson</h3>
                                <p class="text-gray-500 text-sm">CEO, InnovateX</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"Switching to Enterprise saved us hundreds of hours with their custom integrations."</p>
                        <div class="flex mt-3 text-yellow-400">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white font-bold mr-4">AD</div>
                            <div>
                                <h3 class="font-semibold">Alex Davis</h3>
                                <p class="text-gray-500 text-sm">CMO, LaunchPad</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"The analytics in the Professional plan helped us optimize our event ROI by 40%."</p>
                        <div class="flex mt-3 text-yellow-400">
                            ★ ★ ★ ★ ☆
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Payment Modal -->
    <div id="payment-modal" class="payment-modal">
        <div class="payment-container">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold" id="selected-plan-name">Complete Payment</h3>
                <button id="close-payment-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-2">Plan: <span id="plan-name-text" class="font-medium"></span></p>
                <p class="text-gray-600 mb-2">Billing: <span id="billing-period-text" class="font-medium"></span></p>
                <p class="text-gray-600">Amount: <span id="plan-amount-text" class="font-bold text-lg"></span></p>
            </div>
            
            <div id="google-pay-button"></div>
            
            <p class="text-sm text-gray-500 mt-4">
                By completing this payment, you agree to our <a href="#" class="text-blue-600">Terms of Service</a> 
                and <a href="#" class="text-blue-600">Privacy Policy</a>.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#121823] text-white pt-16 pb-8">
        <div class="container mx-auto container-padding">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <!-- Company Info -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <img src="Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                        <span class="text-2xl font-bold ml-2">Ready<span class="gradient-text">Up</span></span>
                    </div>
                    <p class="text-gray-400">
                        Empowering startups with exceptional event management solutions since 2025.
                    </p>
                    <div class="flex space-x-4 pt-2">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 text-xl">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 text-xl">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 text-xl">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300 text-xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index1.html" class="text-gray-400 hover:text-white transition duration-300 block">Home</a></li>
                        <li><a href="about1.html" class="text-gray-400 hover:text-white transition duration-300 block">About Us</a></li>
                        <li><a href="index.html#sectors" class="text-gray-400 hover:text-white transition duration-300 block">Events</a></li>
                        <li><a href="contact.html" class="text-gray-400 hover:text-white transition duration-300 block">Contact</a></li>
                    </ul>
                </div>

                <!-- Events -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Events</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <a href="tech.html" class="text-gray-400 hover:text-white transition duration-300 block">Tech & Innovation</a>
                        <a href="e-com.html" class="text-gray-400 hover:text-white transition duration-300 block">E-commerce</a>
                        <a href="envi.html" class="text-gray-400 hover:text-white transition duration-300 block">Sustainability</a>
                        <a href="finance.html" class="text-gray-400 hover:text-white transition duration-300 block">FinTech</a>
                        <a href="food.html" class="text-gray-400 hover:text-white transition duration-300 block">Food & Beverage</a>
                        <a href="social.html" class="text-gray-400 hover:text-white transition duration-300 block">Social Impact</a>
                        <a href="health.html" class="text-gray-400 hover:text-white transition duration-300 block">Health & Wellness</a>
                        <a href="media.html" class="text-gray-400 hover:text-white transition duration-300 block">Entertainment</a>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Newsletter</h3>
                    <p class="text-gray-400">
                        Subscribe to our newsletter for the latest updates.
                    </p>
                    <form class="flex mt-2">
                        <input 
                            type="email" 
                            placeholder="Your email" 
                            class="flex-grow p-3 rounded-l-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                        <button 
                            type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 rounded-r-lg transition duration-300 flex items-center justify-center"
                        >
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-gray-500 text-sm">
                    &copy; 2025 ReadyUp. All rights reserved.
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition duration-300">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition duration-300">Terms of Service</a>
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition duration-300">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Enhanced Popup -->
    <div id="popup" class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-xl hidden items-center z-50">
        <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <p id="popup-message"></p>
    </div>
    
    <script>
        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Smooth scrolling for all anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Initialize animations when page loads
            // Animate header
            gsap.to("#header", {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "elastic.out(1, 0.5)"
            });
            
            // Slide in plans from right
            gsap.to("#plans", {
                x: 0,
                opacity: 1,
                duration: 1,
                stagger: 0.1,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#plans",
                    start: "top 80%",
                    toggleActions: "play none none none"
                }
            });
            
            // Slide in testimonials from left
            gsap.to("#testimonials", {
                x: 0,
                opacity: 1,
                duration: 1,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#testimonials",
                    start: "top 80%",
                    toggleActions: "play none none none"
                }
            });
            
            // Billing toggle functionality
            const monthlyBtn = document.getElementById('monthlyBtn');
            const annualBtn = document.getElementById('annualBtn');
            
            monthlyBtn.addEventListener('click', () => {
                monthlyBtn.classList.add('bg-blue-600', 'text-white');
                monthlyBtn.classList.remove('text-gray-600', 'hover:text-blue-600');
                annualBtn.classList.remove('bg-blue-600', 'text-white');
                annualBtn.classList.add('text-gray-600', 'hover:text-blue-600');
                
                document.querySelectorAll('.monthly-price').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.annual-price').forEach(el => el.classList.add('hidden'));
            });
            
            annualBtn.addEventListener('click', () => {
                annualBtn.classList.add('bg-blue-600', 'text-white');
                annualBtn.classList.remove('text-gray-600', 'hover:text-blue-600');
                monthlyBtn.classList.remove('bg-blue-600', 'text-white');
                monthlyBtn.classList.add('text-gray-600', 'hover:text-blue-600');
                
                document.querySelectorAll('.annual-price').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.monthly-price').forEach(el => el.classList.add('hidden'));
                
                // Animate price change
                gsap.from(".annual-price:not(.hidden)", {
                    y: -20,
                    opacity: 0,
                    duration: 0.5,
                    stagger: 0.1
                });
            });
            
            // Add hover effects to all plan cards
            const cards = document.querySelectorAll('.plan-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    gsap.to(card, {
                        y: -10,
                        duration: 0.3,
                        ease: "power2.out"
                    });
                });
                
                card.addEventListener('mouseleave', () => {
                    gsap.to(card, {
                        y: 0,
                        duration: 0.3,
                        ease: "power2.out"
                    });
                });
            });
        });
        
        // Plan selection function
        function selectPlan(planName) {
            showPopup('${planName} plan selected');
            
            // Add visual feedback to selected plan
            const cards = document.querySelectorAll('.plan-card');
            cards.forEach(card => {
                card.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
            });
            
            event.currentTarget.closest('.plan-card').classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
            
            // Scroll to testimonials if selecting a paid plan
            if(planName !== 'Starter') {
                gsap.to(window, {
                    duration: 1,
                    scrollTo: "#testimonials",
                    ease: "power2.inOut"
                });
            }
        }
        
        // Show popup notification
        function showPopup(message) {
            const popup = document.getElementById('popup');
            document.getElementById('popup-message').innerText = message;
            
            // Animate in
            gsap.fromTo(popup, 
                { opacity: 0, y: -20 },
                { 
                    opacity: 1, 
                    y: 0,
                    duration: 0.3,
                    ease: "back.out(1.7)",
                    onStart: () => popup.classList.remove('hidden')
                }
            );
            
            // Animate out after delay
            setTimeout(() => {
                gsap.to(popup, {
                    opacity: 0,
                    y: -20,
                    duration: 0.3,
                    ease: "power1.in",
                    onComplete: () => popup.classList.add('hidden')
                });
            }, 2000);
        }
    </script>
    <script>
        // Previous JavaScript remains the same until the selectPlan function
        
        // Global variables to store selected plan details
        let selectedPlan = {
            name: '',
            monthlyPrice: 0,
            annualPrice: 0,
            isAnnual: false
        };
        
        // Modified selectPlan function
        // Modified selectPlan function
function selectPlan(planName) {
    // Add visual feedback to selected plan
    const cards = document.querySelectorAll('.plan-card');
    cards.forEach(card => {
        card.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
    });
    
    event.currentTarget.closest('.plan-card').classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
    
    // For Starter plan, just show the popup
    if (planName === 'Starter') {
        showPopup('Starter plan selected');
        return;
    }
    
    // For Enterprise plan, redirect to email
    if (planName === 'Enterprise') {
        window.location.href = "mailto:sales@readyup.com?subject=Enterprise Plan Inquiry";
        return;
    }
    
    // Store selected plan details
    selectedPlan.name = planName;
    
    // Set prices based on plan (in paise for Google Pay)
    switch(planName) {
        case 'Basic':
            selectedPlan.monthlyPrice = 90000; // ₹900 in paise
            selectedPlan.annualPrice = 840000; // ₹8400 in paise
            break;
        case 'Professional':
            selectedPlan.monthlyPrice = 290000; // ₹2900 in paise
            selectedPlan.annualPrice = 2760000; // ₹27600 in paise (corrected from 25,000)
            break;
    }
    
    // Check if annual billing is selected
    selectedPlan.isAnnual = document.getElementById('annualBtn').classList.contains('bg-blue-600');
    
    // Update modal with plan details
    document.getElementById('plan-name-text').textContent = planName;
    document.getElementById('billing-period-text').textContent = selectedPlan.isAnnual ? 'Annual' : 'Monthly';
    
    const amount = selectedPlan.isAnnual ? selectedPlan.annualPrice : selectedPlan.monthlyPrice;
    document.getElementById('plan-amount-text').textContent = '₹' + (amount/100).toFixed(2);
    
    // Show payment modal
    document.getElementById('payment-modal').style.display = 'flex';
    
    // Initialize Google Pay button
    initializeGooglePay(amount);
    
    // Scroll to testimonials if selecting a paid plan
    gsap.to(window, {
        duration: 1,
        scrollTo: "#testimonials",
        ease: "power2.inOut"
    });
}
        
        // Close payment modal
        document.getElementById('close-payment-modal').addEventListener('click', function() {
            document.getElementById('payment-modal').style.display = 'none';
        });
        
        // Google Pay implementation
        function initializeGooglePay(amountInPaise) {
    // Clear any existing buttons first
    const googlePayButton = document.getElementById('google-pay-button');
    googlePayButton.innerHTML = '';
    
    const paymentsClient = new google.payments.api.PaymentsClient({
        environment: 'TEST' // Change to 'PRODUCTION' for live payments
    });
    
    const baseRequest = {
        apiVersion: 2,
        apiVersionMinor: 0
    };
    
    const allowedCardNetworks = ["AMEX", "DISCOVER", "JCB", "MASTERCARD", "VISA"];
    const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];
    
    const tokenizationSpecification = {
        type: 'PAYMENT_GATEWAY',
        parameters: {
            'gateway': 'example',
            'gatewayMerchantId': 'exampleGatewayMerchantId'
        }
    };
    
    const baseCardPaymentMethod = {
        type: 'CARD',
        parameters: {
            allowedAuthMethods: allowedCardAuthMethods,
            allowedCardNetworks: allowedCardNetworks
        }
    };
    
    const cardPaymentMethod = {
        ...baseCardPaymentMethod,
        tokenizationSpecification: tokenizationSpecification
    };
    
    const isReadyToPayRequest = {
        ...baseRequest,
        allowedPaymentMethods: [baseCardPaymentMethod]
    };
    
    paymentsClient.isReadyToPay(isReadyToPayRequest)
        .then(function(response) {
            if (response.result) {
                const button = paymentsClient.createButton({
                    onClick: onGooglePayButtonClick,
                    buttonColor: 'black', // or 'white'
                    buttonType: 'pay'
                });
                googlePayButton.appendChild(button);
            }
        })
        .catch(function(err) {
            console.error(err);
            // Fallback to other payment methods if Google Pay isn't available
            googlePayButton.innerHTML = `
                <button class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors"
                        onclick="showAlternativePaymentMethods()">
                    Continue with Other Payment
                </button>
            `;
        });
    
    function onGooglePayButtonClick() {
        const paymentDataRequest = {
            ...baseRequest,
            allowedPaymentMethods: [cardPaymentMethod],
            transactionInfo: {
                totalPriceStatus: 'FINAL',
                totalPrice: amountInPaise.toString(),
                currencyCode: 'INR',
                countryCode: 'IN'
            },
            merchantInfo: {
                merchantName: 'ReadyUp Events',
                merchantId: 'BCR2DN4TXL6BETX6' // Your merchant ID
            }
        };
        
        paymentsClient.loadPaymentData(paymentDataRequest)
            .then(function(paymentData) {
                // Handle successful payment
                console.log('Payment successful', paymentData);
                processPayment(paymentData);
            })
            .catch(function(err) {
                console.error(err);
                // Handle payment failure
                showPopup('Payment failed. Please try again.');
            });
    }
}
            
        
        function processPayment(paymentData) {
            // In a real implementation, you would send this to your server
            // for verification and processing
            console.log('Processing payment:', paymentData);
            
            // Simulate server processing
            setTimeout(() => {
                document.getElementById('payment-modal').style.display = 'none';
                showPopup('Payment successful! Your subscription is now active.');
                
                // In a real app, you would redirect to a success page or update user status
            }, 2000);
        }
        // Close payment modal
            
        function showAlternativePaymentMethods() {
            // This would show other payment options in a real implementation
            showPopup('Redirecting to payment gateway...');
            
            // Simulate redirect
            setTimeout(() => {
                document.getElementById('payment-modal').style.display = 'none';
                window.open('https://razorpay.com/payment-gateway/', '_blank');
            }, 1000);
        }
    </script>
    
</body>
</html>