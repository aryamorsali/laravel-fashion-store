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
                             <form action="{{ route('admin.notifications.mark-as-read') }}" method="POST">
                                 @csrf
                                 <button type="submit" class="btn btn-sm btn-link p-0 mark-read">
                                     Mark all as read
                                 </button>
                             </form>
                         </li>
                         @foreach ($notifications as $notification)
                             <li>
                                 <a class="notif-item" href="{{ $notification->data['url'] }}">
                                     <span
                                         class="notification-icon @switch($notification->data['event'])
                                         @case('new_order')
                                             bg-primary
                                             @break
                                                @case('low_stock')
                                             bg-warning
                                             @break
                                                @case('payment_failed')
                                             bg-danger
                                             @break
                                                @case('new_ticket')
                                             bg-info
                                             @break
                                                   @case('new_user')
                                             bg-secondary
                                             @break
                                                     @case('new_product_comment')
                                             bg-success
                                             @break
                                                     @case('new_post_comment')
                                             bg-dark
                                             @break
                                             @default
                                     @endswitch">
                                         @php
                                             $icon = match ($notification->data['event'] ?? '') {
                                                 'new_order' => 'shopping-cart',
                                                 'low_stock' => 'box-open',
                                                 'payment_failed' => 'exclamation-triangle',
                                                 'new_ticket' => 'headset',
                                                 'new_user' => 'user-plus',
                                                 'new_post_comment' => 'comment-dots',
                                                 'new_product_comment' => 'comment-alt',
                                             };
                                         @endphp

                                         <i class="fas fa-{{ $icon }}"></i>
                                     </span>
                                     <div class="notif-body">
                                         <p class="notif-title">{!! $notification->data['message'] !!} <span class="dot"></span>
                                         </p>
                                         <p class="notif-sub">{{ $notification->created_at->diffForHumans() }}</p>
                                     </div>
                                 </a>
                             </li>
                         @endforeach
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
