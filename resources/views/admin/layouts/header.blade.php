     <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
         <!-- Navbar Brand-->
         <a class="navbar-brand ps-3" href="index.html">Coza Store</a>

         <!-- Sidebar Toggle-->
         <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                 class="fas fa-bars"></i></button>
         <!-- Navbar Search-->
         <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
             <div class="input-group">
                 <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                     aria-describedby="btnNavbarSearch" />
                 <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i
                         class="fas fa-search"></i></button>
             </div>
         </form>
         <!-- Navbar-->
         <!-- Navbar-->
         <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 d-flex flex-row align-items-center">

             <!-- Notifications Bell -->
             @canany(['manage-orders', 'view-inventory', 'view-warehouse', 'manage-tickets', 'manage-payments'])
                 <li class="nav-item dropdown me-1">
                     <a class="nav-link position-relative" id="navbarDropdownNotifications" href="#" role="button"
                         data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Notifications">
                         <i class="far fa-bell fa-fw"></i>
                         <span class="notif-count">{{ $notifications->count() }}</span>
                     </a>

                     <ul class="dropdown-menu dropdown-menu-end notif-menu shadow-sm"
                         aria-labelledby="navbarDropdownNotifications">
                         <li class="notif-head">
                             Notifications
                             <a href="#!" class="mark-read">Mark all as read</a>
                         </li>
                         @foreach ($notifications as $notification)
                             <li>
                                 <a class="notif-item" href="#!">
                                     <span class="notification-icon @if($notification['event'] == 'order') bg-primary @endif">
                                         <i class="fas fa-shopping-cart"></i>
                                     </span>
                                     <div class="notif-body">
                                         <p class="notif-title">New order <b>#1024</b> received <span class="dot"></span>
                                         </p>
                                         <p class="notif-sub">5 min ago</p>
                                     </div>
                                 </a>
                             </li>
                         @endforeach


                         {{-- <li>
                             <a class="notif-item" href="#!">
                                 <span class="notification-icon bg-danger">
                                     <i class="fas fa-exclamation-triangle"></i>
                                 </span>
                                 <div class="notif-body">
                                     <p class="notif-title">Payment failed for order <b>#1023</b> <span
                                             class="dot"></span>
                                     </p>
                                     <p class="notif-sub">32 min ago</p>
                                 </div>

                             </a>
                         </li>

                         <li>
                             <a class="notif-item" href="#!">
                                 <span class="notification-icon bg-warning">
                                     <i class="fas fa-box-open"></i>
                                 </span>
                                 <div class="notif-body">

                                     <p class="notif-title">Low stock: <b>Black T-Shirt — L</b> <span class="dot"></span>
                                     </p>
                                     <p class="notif-sub">2 hours ago</p>
                                 </div>

                             </a>
                         </li>

                         <li>
                             <a class="notif-item" href="#!">
                                 <span class="notification-icon bg-info">
                                     <i class="fas fa-headset"></i>
                                 </span>
                                 <div class="notif-body">

                                     <p class="notif-title">New support ticket <b>#88</b> <span class="dot"></span></p>
                                     <p class="notif-sub">Yesterday</p>
                                 </div>

                             </a>
                         </li> --}}

                         <li class="notif-foot"><a href="{{ route('admin.notifications') }}">View all notifications</a></li>
                     </ul>
                 </li>
             @endcanany


             <li class="nav-item dropdown">
                 <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                     data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                 <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                     <li><a class="dropdown-item" href="#!">Settings</a></li>
                     <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                     <li>
                         <hr class="dropdown-divider" />
                     </li>
                     <li><a class="dropdown-item" href="#!">Logout</a></li>
                 </ul>
             </li>
         </ul>
     </nav>
