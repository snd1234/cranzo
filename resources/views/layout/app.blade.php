<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title>Laboratory — Tailwind Demo</title>
    <!-- <script src="assets/js/tailwind.3.4.17.js"></script> -->

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <script src="assets/js/lucide-icon.js"></script> -->
    <link rel="stylesheet" href="{{asset('front/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('front/css/fonts.css')}}">
    <link rel="stylesheet" href="{{asset('front/css/style.css')}}">

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <!-- Header -->
    <header class="header shadow-md sticky top-0 z-20">
        <?php  
        //echo "<pre>";print_r($headerCategories);die;
        ?>
        <div class="max-w-full mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="company-logo">
                    <a href="{{ url('/') }}"><img src="{{asset('front/images/IGB-Logo_white.png')}}" alt="IGB Logo"></a>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <div class="header-right-top flex items-end gap-10 pt-5">
                    <div class="contact-info flex items-center space-x-6">
                        <a href="#" class="flex items-center space-x-2 group">
                            <i class="fa-solid fa-phone"></i>
                            <span>+91 9876543210</span>
                        </a>
                        <a href="#" class="flex items-center space-x-2 group">
                            <i class="fa-regular fa-envelope"></i>
                            <span>info@yourmail.com</span>
                        </a>
                    </div>
                    <div class="social-icons flex items-center justify-end space-x-5">

                        <a href="#" class="icon-hover">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="icon-hover">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="icon-hover">
                        <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="#" class="icon-hover">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
        
                        <a href="#" class="icon-hover">
                        <i class="fa-brands fa-youtube"></i>
                        </a>
        
                        <!-- Mobile menu button -->
                        <button onclick="toggleMenu()" class="lg:hidden focus:outline-none">
                            <i data-lucide="menu" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>
                <nav class="hidden lg:block">
                    <ul class="menu-items flex items-center space-x-10 text-sm text-gray-600">
                        <li><a href="{{ url('/about-us') }}" class="uppercase flex items-center">About us</a></li>
                        <li class="submenu-trigger">

                            <a href="{{ url('/products') }}" class="uppercase flex items-center gap-2">Products <span class="arrow"><i data-lucide="chevron-down"></i></span></a>
                            <div class="submenu bg-white">
                                <div class="grid grid-cols-3 gap-10">
                                   @foreach($headerCategories as $category)
                                    @if($category->subcategories->contains(fn($sub) => $sub->products->isNotEmpty()))
                                        <div class="space-y-3">
                                            <h6 class="mb-4">
                                                {{ $category->name }}
                                            </h6>
                                            @if($category->subcategories->isNotEmpty())
                                                <ul class="p-0 m-0 space-y-3">
                                                    @foreach($category->subcategories as $sub)
                                                        @if($sub->products->isNotEmpty())
                                                        <li class="sub-submenu-trigger">
                                                            <a href="#" class="flex items-center gap-4">
                                                                <!-- <i class="fa-solid fa-plus"></i> -->
                                                                <span>{{ $sub->name }}</span>
                                                            </a>
                                                            <div class="sub-submenu">
                                                                <span class="angle-left"></span>

                                                                @foreach($sub->products as $product)
                                                                    <a href="{{ url('product/' . $product->slug) }}">
                                                                        {{ $product->title }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            
                        </li>
                        <li><a href="{{ url('/our-partners') }}" class="uppercase flex items-center">Our partners</a></li>
                        <li><a href="{{ url('/events') }}" class="uppercase flex items-center">Events</a></li>
                        <li><a href="{{ url('/news-room') }}" class="uppercase flex items-center">Newsroom</a></li>
                        <li><a href="{{ url('/contact-us') }}" class="uppercase flex items-center">Contact Us</a></li>
                    </ul>
                </nav>
               
            </div>
        </div>
        <!-- Mobile Nav -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
            <ul class="menu-items flex flex-col p-4 space-y-3 text-sm text-gray-700">
                <li><a href="#" class="">Products</a></li>
                <li><a href="#" class="">Applications</a></li>
                <li><a href="#" class="">Solutions</a></li>
                <li><a href="#" class="">Service & Support</a></li>
                <li><a href="#" class="">About</a></li>
                <li><a href="#" class="">News</a></li>
                <li><a href="#" class="">Contact</a></li>
            </ul>
        </div>
    </header>

    @yield('content')
   
    <!--footer -->

    <footer>
        <div class="footer p-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div>
                    <div class="mb-5">
                        <img src="{{asset('front/images/IGB-Logo.png')}}" class="w-full object-cover" />
                    </div>
                    <p >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum
                        magna, et dapibus lacus. Duis nec vestibulum magna, et dapibus lacus.</p>
                </div>
                <div>
                    <h3 class="mb-5 text-white uppercase">Our Products</h3>
                    <div class="grid grid-cols-1 gap-1">
                        <a href="#" class="inline-block text-white transition duration-300">
                            Clinical Diagnostics & HLA
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Scientific Resources
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Applied Markets
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Publications
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Basic Research
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            New & Featured Products
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="mb-5 text-white uppercase">Customer Service</h3>
                    <div class="grid grid-cols-1 gap-1">
                        <a href="#" class="inline-block text-white transition duration-300">
                            About IGB
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Careers
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Contact Us
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Promotions
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Online Registration
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Events Calendar
                        </a>
                        <a href="#" class="inline-block text-white transition duration-300">
                            Global Partners
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="mb-5 text-white uppercase">Contact Info</h3>
                    <ul class="space-y-2 mb-6">
                        <li class="flex flex-col gap-1">
                            <h6 class="text-white uppercase">Address :</h6>
                            <p>123 Street Name, City, England</p>
                        </li>

                        <li class="flex flex-col gap-1">
                            <h6 class="text-white uppercase">Phone :</h6>
                            <a href="tel:(123)456-7890" class=" hover:underline">(123) 456-7890</a>
                        </li>

                        <li class="flex flex-col gap-1">
                            <h6 class="text-white uppercase">Email :</h6>
                            <a href="mailto:mail@example.com" class=" hover:underline">
                                mail@example.com
                            </a>
                        </li>

                        <li class="flex flex-col gap-1">
                            <h6 class="text-white uppercase">Working Days/Hours :</h6>
                            <p>Mon - Sun / 9:00 AM - 8:00 PM</p>
                        </li>
                    </ul>
                    <div class="social-icons flex space-x-6">
                        <a href="#" class="icon-hover">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="icon-hover">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="icon-hover">
                        <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="#" class="icon-hover">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
        
                        <a href="#" class="icon-hover">
                        <i class="fa-brands fa-youtube"></i>
                        </a>
                    </div>
                </div>


            </div>
        </div>
        <div class="copyright-socials border-t border-gray-700 py-4 px-12 ">
            <p class="flex items-center justify-center"><i data-lucide="copyright" class="w-4 h-4 mx-2"></i>
                Integrated Gulf Biosystems, 2025. All Rights Reserved</p>
        </div>
   </footer>

<div class="sideButtons">
    <div class="phone-widget">
        <i class="fa-solid fa-phone"></i>
        <a href="tel:+919876543210">+91 98765 43210</a>
    </div>
    <div class="phone-widget email">
        <i class="fa-regular fa-envelope"></i>
        <a href="mailto:info@yourmail.com">info@yourmail.com</a>
    </div>
</div>

<script src="{{asset('front/js/jquery.min.js')}}"></script>
<script src="{{asset('front/js/swiper-bundle.min.js')}}"></script>
<script src="{{asset('front/js/custom.js')}}"></script>




