   {{-- Sidebar --}}
   <div class="col-lg-3 col-md-4 m-b-30">
       <div class="bor10 p-lr-25 p-tb-25">
           <div class="flex-w flex-t p-b-22 bor12">
               <div>
                   <h4 class="mtext-107 cl2 p-b-5">
                       {{ auth()->user()->full_name ?? '-' }}
                   </h4>

                   <span class="stext-109 cl6">
                       {{ auth()->user()->email ?? auth()->user()->mobile }}
                   </span>
               </div>
           </div>

           <nav class="profile-sidebar-menu">
               <a href="{{ route('customer.profile.profile') }}" class="profile-menu-item {{ Route::is('customer.profile.profile') ? 'active' : '' }}">
                   <i class="zmdi zmdi-account-circle"></i>
                   Profile Details
               </a>

               <a href="{{route('customer.profile.my-orders')}}" class="profile-menu-item">
                   <i class="zmdi zmdi-shopping-basket"></i>
                   My Orders
               </a>

               <a href="{{route('customer.profile.my-addresses')}}" class="profile-menu-item {{ Route::is('customer.profile.my-addresses') ? 'active' : '' }}">
                   <i class="zmdi zmdi-pin"></i>
                   My Addresses
               </a>

               <a href="{{route('customer.profile.ticket.index')}}" class="profile-menu-item">
                   <i class="zmdi zmdi-headset-mic"></i>
                   My Tickets
               </a>

               <a href="{{route('customer.profile.my-favorites')}}" class="profile-menu-item {{ Route::is('customer.profile.my-favorites') ? 'active' : '' }}">
                   <i class="zmdi zmdi-favorite-outline"></i>
                   Wishlist
               </a>

               <form action="{{ route('logout') }}" method="POST">
                   @csrf

                   <button type="submit" class="profile-menu-item profile-logout">
                       <i class="zmdi zmdi-power"></i>
                       Logout
                   </button>
               </form>
           </nav>
       </div>
   </div>
