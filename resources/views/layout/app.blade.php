<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Integrated Gulf Biosystems</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('front/js/lucide-icon.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('front/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 shadow-md sticky top-0 z-20">
        <div class="max-w-full mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="text-green-600 font-bold text-lg">
                    <a href="{{ url('/') }}"><img src="{{ asset('front/images/IGB-Logo.png') }}" class="h-20" alt="IGB Logo"></a>
                </div>
            </div>
            <div class="flex items-center space-x-4 text-sm">
                <nav class="hidden md:block">
                    <ul class="menu-items flex space-x-6 text-sm text-gray-600">
                        <li><a href="#" class="uppercase hover:text-green-600">About us</a></li>
                        <li><a href="products.html" class="uppercase hover:text-green-600">Products</a></li>
                        <li><a href="#" class="uppercase hover:text-green-600">Our partners</a></li>
                        <li><a href="#" class="uppercase hover:text-green-600">Events</a></li>
                        <li><a href="#" class="uppercase hover:text-green-600">Newsroom</a></li>
                        <li><a href="#" class="uppercase hover:text-green-600">Contact Us</a></li>
                    </ul>
                </nav>
                <!-- Mobile menu button -->
                <button onclick="toggleMenu()" class="md:hidden focus:outline-none">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Nav -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
            <ul class="menu-items flex flex-col p-4 space-y-3 text-sm text-gray-700">
                <li><a href="#" class="hover:text-green-600">Products</a></li>
                <li><a href="#" class="hover:text-green-600">Applications</a></li>
                <li><a href="#" class="hover:text-green-600">Solutions</a></li>
                <li><a href="#" class="hover:text-green-600">Service & Support</a></li>
                <li><a href="#" class="hover:text-green-600">About</a></li>
                <li><a href="#" class="hover:text-green-600">News</a></li>
                <li><a href="#" class="hover:text-green-600">Contact</a></li>
            </ul>
        </div>
    </header>
    @yield('content')

    <!--footer -->
    <footer>
        <div class="footer py-12 px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <h3 class="text-2xl mb-5 pb-3 border-b border-white text-white">Support</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Request a Quote
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Service & Support Request
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Training & Custom Project
                        </a>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-2xl mb-5 pb-3 border-b border-white text-white">Our Products</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Clinical Diagnostics & HLA
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Scientific Resources
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Applied Markets
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Publications
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Basic Research
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> New & Featured Products
                        </a>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-2xl mb-5 pb-3 border-b border-white text-white">Corporate</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> About IGB
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Careers
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Contact Us
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Promotions
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Online Registration
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Events Calendar
                        </a>
                        <a href="#"
                            class="flex items-center inline-block text-lg text-white hover:underline transition duration-300">
                            <i data-lucide="chevrons-right" class="w-5 h-5 mr-2"></i> Global Partners
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-socials p-4 flex flex-col md:flex-row items-center justify-between">
            <p class="flex items-center text-white">Copyright <i data-lucide="copyright" class="w-4 h-4 mx-2"></i>
                Integrated Gulf Biosystems, 2022. All Rights Reserved</p>
            <!-- Social Icons -->
            <div class="flex space-x-6">
                <a href="#" aria-label="Facebook" class="text-white hover:text-blue-500 transition">
                    <i data-lucide="facebook" class="w-6 h-6"></i>
                </a>
                <a href="#" aria-label="Instagram" class="text-white  hover:text-blue-500 transition">
                    <i data-lucide="instagram" class="w-6 h-6"></i>
                </a>
                <a href="#" aria-label="LinkedIn" class="text-white hover:text-blue-500 transition">
                    <i data-lucide="linkedin" class="w-6 h-6"></i>
                </a>
                <a href="#" aria-label="YouTube" class="text-white hover:text-blue-500 transition">
                    <i data-lucide="youtube" class="w-6 h-6"></i>
                </a>
            </div>
        </div>

    </footer>