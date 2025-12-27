<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadyUp - E-commerce & Retail Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .gradient-text {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .hero-section {
            background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
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
        .event-card {
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        .event-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .event-card-inner {
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        .event-card:hover .event-card-inner {
            transform: rotateY(5deg);
        }
        .date-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .tag {
            display: inline-block;
            background-color: #e0e7ff;
            color: #4f46e5;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 6px;
            margin-bottom: 6px;
        }
        .fade-in {
            animation: fadeIn 0.8s ease-in forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
        .delay-7 { animation-delay: 0.7s; }
        .delay-8 { animation-delay: 0.8s; }
        .delay-9 { animation-delay: 0.9s; }
        .delay-10 { animation-delay: 1s; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
       <!-- Header -->
       <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto container-padding py-3 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index1.php" class="flex items-center">
                    <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                </a>
            </div>
            
            <nav class="hidden md:block">
                <ul class="flex space-x-8">
                    <li><a href="index1.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                    <li><a href="about1.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                    <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Events</a></li>
                    <li><a href="contact1.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
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
                        <img src="images/search.png" alt="s" class="mx-0.5 w-full h-full">
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
            <li><a href="index1.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
            <li><a href="about1.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
            <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Events</a></li>
            <li><a href="contact1.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
        </ul>
    </div>
    <!-- Hero Section -->
    <section class="hero-section flex items-center min-h-[50vh]">
        <div class="container mx-auto container-padding py-16 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight animate_animated animate_fadeInDown">
                Upcoming <span class="gradient-text">E-commerce & Retail Events</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto animate_animated animatefadeIn animate_delay-1s">
                Discover cutting-edge retail conferences, e-commerce expos, digital marketing summits, and supply chain workshops worldwide.
            </p>
            <div class="animate_animated animatefadeIn animate_delay-2s">
                <a href="#events" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-full transition duration-300">
                    Explore Events <i class="fas fa-arrow-down ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Events Filter Section -->
    <section class="py-8 bg-white sticky top-16 z-40 shadow-sm">
        <div class="container mx-auto container-padding">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Filter Events</h2>
                <div class="flex flex-wrap gap-3">
                    <button class="filter-btn active bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium transition-all" data-filter="all">
                        All Events
                    </button>
                    <button class="filter-btn bg-purple-100 text-purple-800 px-4 py-2 rounded-full font-medium transition-all" data-filter="conference">
                        Conferences
                    </button>
                    <button class="filter-btn bg-green-100 text-green-800 px-4 py-2 rounded-full font-medium transition-all" data-filter="summit">
                        Summits
                    </button>
                    <button class="filter-btn bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-medium transition-all" data-filter="workshop">
                        Workshops
                    </button>
                    <button class="filter-btn bg-red-100 text-red-800 px-4 py-2 rounded-full font-medium transition-all" data-filter="expo">
                        Expos
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Listing Section -->
    <section class="py-16 bg-gray-50" id="events">
        <div class="container mx-auto container-padding">
            <?php
            $conn = new mysqli("localhost", "root", "", "eventstore");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $today = date('Y-m-d');
            $conn->query("DELETE FROM ecom WHERE date < '$today'");
            $events = $conn->query("SELECT * FROM ecom WHERE date >= '$today' ORDER BY date ASC");
            ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $delay = 1;
                while($row = $events->fetch_assoc()): 
                    // Map database categories to our filter categories
                    $filter_category = strtolower($row['category']);
                    if (strpos($filter_category, 'summit') !== false) {
                        $filter_category = 'summit';
                    } elseif (strpos($filter_category, 'workshop') !== false) {
                        $filter_category = 'workshop';
                    } elseif (strpos($filter_category, 'expo') !== false) {
                        $filter_category = 'expo';
                    } else {
                        $filter_category = 'conference'; // default
                    }
                ?>
                <div class="event-card fade-in delay-<?= $delay++ % 10 ?>" data-category="<?= $filter_category ?>">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden h-full flex flex-col event-card-inner">
                        <div class="relative">
                            <?php if (!empty($row['image'])): ?>
                                <img src="<?= htmlspecialchars($row['image']) ?>" 
                                    alt="<?= htmlspecialchars($row['name']) ?>" 
                                    class="w-full h-48 object-cover"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='https://via.placeholder.com/400x200?text=Event+Image';">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">No Image Available</span>
                                </div>
                            <?php endif; ?>
                            <div class="date-badge">
                                <span><?= date('M d, Y', strtotime($row['date'])) ?></span>
                            </div>
                        </div>
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <span class="tag <?= 
                                    $filter_category === 'hackathon' ? 'bg-purple-100 text-purple-800' : 
                                    ($filter_category === 'workshop' ? 'bg-green-100 text-green-800' : 
                                    ($filter_category === 'webinar' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'))
                                ?>">
                                    <?= htmlspecialchars($row['category']) ?>
                                </span>
                                <span class="text-sm text-gray-500"><?= htmlspecialchars($row['place']) ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3"><?= htmlspecialchars($row['name']) ?></h3>
                            <p class="text-gray-600 mb-4"><?= htmlspecialchars($row['description']) ?></p>
                            <div class="mt-auto">
                                <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-full transition duration-300">
                                    Learn More <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                
                <?php if ($events->num_rows === 0): ?>
                    <div class="col-span-full text-center py-12">
                        <h3 class="text-2xl font-bold text-gray-700">No upcoming events found</h3>
                        <p class="text-gray-500 mt-2">Check back later for new events!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="container mx-auto container-padding text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Have an E-commerce Event to Promote?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                List your event on ReadyUp and reach thousands of retail professionals, e-commerce entrepreneurs, and digital marketers.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
               
                <a href="addevent.html" class="bg-transparent hover:bg-white/10 text-white font-semibold py-3 px-8 rounded-full border border-white transition duration-300">
                    Promote Your Event
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
        <!-- Footer -->
        <footer class="bg-[#121823] text-white pt-16 pb-8" id="contact">
            <div class="container mx-auto container-padding">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    <!-- Company Info -->
                    <div>
                        <div class="flex items-center mb-4">
                            <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                            <span class="text-2xl font-bold ml-2">Ready<span class="gradient-text">Up</span></span>
                        </div>
                        <p class="text-gray-400 mb-4">
                            Empowering startups with exceptional event management solutions since 2025.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                <i class="fab fa-linkedin text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="index1.php" class="text-gray-400 hover:text-white transition duration-300">Home</a></li>
                            <li><a href="about1.html" class="text-gray-400 hover:text-white transition duration-300">About Us</a></li>
                            <li><a href="#sectors" class="text-gray-400 hover:text-white transition duration-300">Events</a></li>
                            <li><a href="contact1.html" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                            </ul>
                    </div>
                    
                    <!-- Events -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Events</h3>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                            <a href="tech1.html" class="text-gray-400 hover:text-white transition duration-300 block">Tech & Innovation</a>
                            <a href="e-com1.php" class="text-gray-400 hover:text-white transition duration-300 block">E-commerce</a>
                            <a href="envi1.php" class="text-gray-400 hover:text-white transition duration-300 block">Sustainability</a>
                            <a href="finance1.php" class="text-gray-400 hover:text-white transition duration-300 block">FinTech</a>
                            <a href="food1.php" class="text-gray-400 hover:text-white transition duration-300 block">Food & Beverage</a>
                            <a href="social1.php" class="text-gray-400 hover:text-white transition duration-300 block">Social Impact</a>
                            <a href="health1.php" class="text-gray-400 hover:text-white transition duration-300 block">Health & Wellness</a>
                            <a href="media1.php" class="text-gray-400 hover:text-white transition duration-300 block">Entertainment</a>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt mt-1 mr-3 text-blue-400"></i>
                                <span class="text-gray-400">Block 33, Lovely Professional University, Jalandhar - Delhi G.T. Road, Phagwara, Punjab (India) - 144411</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone-alt mr-3 text-blue-400"></i>
                                <span class="text-gray-400">+91 95396 71145</span>
                            </li>
                            <li class="flex items-center">
                                <a href="mailto:readyup142442@gmail.com" class="flex items-center hover:text-blue-300 transition duration-300">
                                    <i class="fas fa-envelope mr-3 text-blue-400"></i>
                                    <span class="text-gray-400 hover:text-blue-300">readyup142442@gmail.com</span>
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-clock mr-3 text-blue-400"></i>
                                <span class="text-gray-400">Mon-Fri: 9AM - 6PM</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-500 text-sm mb-4 md:mb-0">
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

            // Event filtering functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            const eventCards = document.querySelectorAll('.event-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Update active state
                    filterButtons.forEach(btn => btn.classList.remove('active', 'bg-blue-600', 'text-white'));
                    button.classList.add('active', 'bg-blue-600', 'text-white');
                    
                    const filter = button.dataset.filter;
                    
                    // Filter events
                    eventCards.forEach(card => {
                        if (filter === 'all' || card.dataset.category === filter) {
                            card.style.display = 'block';
                            card.classList.add('animate_animated', 'animate_fadeIn');
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Animate elements when they come into view
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.fade-in');
                
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    
                    if (elementPosition < windowHeight - 100) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            };

            // Initial check
            animateOnScroll();
            
            // Check on scroll
            window.addEventListener('scroll', animateOnScroll);
        });

        document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector('input[placeholder*="Search"]');
        const searchButton = searchInput?.nextElementSibling;

        // Redirect map for keywords
        const redirectMap = {
            "home": "index1.php",
            "about": "about1.html",
            "events": "index1.php#sectors",
            "contact": "contact1.html",
            "message": "contact1.html#message",
            "customer care":"contact1.html#message",
            "customer": "contact1.html#message",
            "tech": "tech1.html",
            "innovation": "tech1.html",
            "sustainability": "envi1.php",
            "sustainable": "envi1.php",
            "sustainable development": "envi1.php",
            "environment": "envi1.php",
            "food": "food1.php",
            "beverage": "food1.php",
            "health": "health1.php",
            "wellness": "health1.php",
            "fintech": "finance1.php",
            "finance": "finance1.php",
            "financial": "finance1.php",
            "social impact": "social1.php",
            "social": "social1.php",
            "impact": "social1.php",
            "e-commerce": "e-com1.php",
            "ecom": "e-com1.php",
            "retail": "e-com1.php",
            "entertainment": "media1.php",
            "media": "media1.php"
        };

        function redirectBasedOnSearch() {
            const query = searchInput.value.toLowerCase().trim();
            for (const keyword in redirectMap) {
                if (query.includes(keyword)) {
                    window.location.href = redirectMap[keyword];
                    return;
                }
            }
            alert("No matching page found for your search.");
        }

        // Handle Enter key
        searchInput.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                redirectBasedOnSearch();
            }
        });

        // Handle search button click
        searchButton?.addEventListener("click", function (event) {
            event.preventDefault();
            redirectBasedOnSearch();
        });
    });
    </script>
</body>
</html>