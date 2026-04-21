 <!-- Header -->
 <header class="{{ Route::is('customer.home') ? '' : 'header-v4' }}">

     <!-- Header desktop -->
     <div class="container-menu-desktop">
         <!-- Topbar -->
         <div class="top-bar">
             <div class="content-topbar flex-sb-m h-full container">
                 <div class="left-top-bar">
                     Free shipping for standard order over $100
                 </div>

                 <div class="right-top-bar flex-w h-full">
                     <a href="#" class="flex-c-m trans-04 p-lr-25">
                         Help & FAQs
                     </a>

                     <a href="#" class="flex-c-m trans-04 p-lr-25">
                         My Account
                     </a>
                 </div>
             </div>
         </div>

         <div class="wrap-menu-desktop">
             <nav class="limiter-menu-desktop container">

                 <!-- Logo desktop -->
                 <a href="{{ route('customer.home') }}" class="logo">
                     <img src="{{ asset('customer-assets/images/icons/logo-01.png') }}" alt="IMG-LOGO">
                 </a>

                 <!-- Menu desktop -->
                 <div class="menu-desktop">
                     <ul class="main-menu">
                         <li class="{{ Route::is('customer.home') ? 'active-menu' : '' }}">
                             <a href="{{ route('customer.home') }}">Home</a>

                         </li>

                         <li class="{{ Route::is('customer.products') ? 'active-menu' : '' }}">
                             <a href="{{ route('customer.products') }}">Shop</a>
                         </li>

                         {{-- <li class="label1" data-label1="hot">
                             <a href="shoping-cart.html">Features</a>
                         </li> --}}

                         <li class="{{ Route::is('customer.blog') ? 'active-menu' : '' }}">
                             <a href="{{ route('customer.blog') }}">Blog</a>
                         </li>

                         <li class="{{ Route::is('customer.about') ? 'active-menu' : '' }}">
                             <a href="{{ route('customer.about') }}">About</a>
                         </li>

                         <li class="{{ Route::is('customer.contact') ? 'active-menu' : '' }}">
                             <a href="{{ route('customer.contact') }}">Contact</a>
                         </li>
                     </ul>
                 </div>

                 <!-- Icon header -->
                 <div class="wrap-icon-header flex-w flex-r-m">
                     <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                         <i class="zmdi zmdi-search"></i>
                     </div>

               

                     <div class="d-inline px-md-3">
                         @auth
                             <button
                                 class="dropdown-toggle profile-button icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11"
                                 type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                 <i class="zmdi zmdi-account"></i>
                             </button>
                             <div class="dropdown-menu dropdown-menu-end mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-xl py-1"
                                 aria-labelledby="dropdownMenuButton1">
                                 <a class="dropdown-item flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100"
                                     href="#">
                                     <i class="zmdi zmdi-account text-base mr-2"></i>User Profile
                                 </a>
                                 <a class="dropdown-item flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100"
                                     href="#">
                                     <i class="zmdi zmdi-case text-base mr-2"></i>Orders
                                 </a>
                                 <a class="dropdown-item flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100"
                                     href="#">
                                     <i class="zmdi zmdi-favorite-outline text-base mr-2"></i>Wishlist
                                 </a>
                                 <hr class="border-t border-gray-200 my-1">
                                 <a class="dropdown-item flex items-center px-4 py-2 text-red-600 hover:bg-red-50"
                                     href="{{ route('logout') }}">
                                     <i class="zmdi zmdi-power text-base mr-2"></i>Logout
                                 </a>
                             </div>
                         @endauth

                         @guest
                             <a href="{{ route('auth.login-register.form') }}"
                                 class="btn btn-outline-dark btn-sm px-3 py-2">
                                 Login / Register
                             </a>
                         @endguest
                     </div>
                 </div>
             </nav>
         </div>
     </div>

     <!-- Header Mobile -->
     <div class="wrap-header-mobile">
         <!-- Logo moblie -->
         <div class="logo-mobile">
             <a href="{{ route('customer.home') }}"><img src="{{ asset('customer-assets/images/icons/logo-01.png') }}"
                     alt="IMG-LOGO"></a>
         </div>

         <!-- Icon header -->
         <div class="wrap-icon-header flex-w flex-r-m m-r-15">
             <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                 <i class="zmdi zmdi-search"></i>
             </div>

             @auth
                 <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"
                     data-notify="2">
                     <i class="zmdi zmdi-shopping-cart"></i>
                 </div>
             @endauth

             <a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti"
                 data-notify="0">
                 <i class="zmdi zmdi-favorite-outline"></i>
             </a>
         </div>

         <!-- Button show menu -->
         <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
             <span class="hamburger-box">
                 <span class="hamburger-inner"></span>
             </span>
         </div>
     </div>


     <!-- Menu Mobile -->
     <div class="menu-mobile">
         <ul class="topbar-mobile">
             <li>
                 <div class="left-top-bar">
                     Free shipping for standard order over $100
                 </div>
             </li>

             <li>
                 <div class="right-top-bar flex-w h-full">
                     <a href="#" class="flex-c-m p-lr-10 trans-04">
                         Help & FAQs
                     </a>

                     <a href="#" class="flex-c-m p-lr-10 trans-04">
                         My Account
                     </a>

                     <a href="#" class="flex-c-m p-lr-10 trans-04">
                         EN
                     </a>

                     <a href="#" class="flex-c-m p-lr-10 trans-04">
                         USD
                     </a>
                 </div>
             </li>
         </ul>

         <ul class="main-menu-m">
             <li>
                 <a href="index.html">Home</a>
                 <ul class="sub-menu-m">
                     <li><a href="index.html">Homepage 1</a></li>
                     <li><a href="home-02.html">Homepage 2</a></li>
                     <li><a href="home-03.html">Homepage 3</a></li>
                 </ul>
                 <span class="arrow-main-menu-m">
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                 </span>
             </li>

             <li>
                 <a href="product.html">Shop</a>
             </li>

             <li>
                 <a href="shoping-cart.html" class="label1 rs1" data-label1="hot">Features</a>
             </li>

             <li>
                 <a href="blog.html">Blog</a>
             </li>

             <li>
                 <a href="about.html">About</a>
             </li>

             <li>
                 <a href="contact.html">Contact</a>
             </li>
         </ul>
     </div>

     <!-- Modal Search -->
     <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
         <div class="container-search-header">
             <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                 <img src="{{ asset('customer-assets/images/icons/icon-close2.png') }}" alt="CLOSE">
             </button>

             <form class="wrap-search-header flex-w p-l-15">
                 <button class="flex-c-m trans-04">
                     <i class="zmdi zmdi-search"></i>
                 </button>
                 <input class="plh3" type="text" name="search" placeholder="Search...">
             </form>
         </div>
     </div>
 </header>

 <!-- Cart -->
 @auth
     <div class="wrap-header-cart js-panel-cart">
         <div class="s-full js-hide-cart"></div>

         <div class="header-cart flex-col-l p-l-65 p-r-25">
             <div class="header-cart-title flex-w flex-sb-m p-b-8">
                 <span class="mtext-103 cl2">
                     Your Cart
                 </span>

                 <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                     <i class="zmdi zmdi-close"></i>
                 </div>
             </div>

           

         </div>
     </div>
 @endauth
