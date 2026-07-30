       <div id="layoutSidenav_nav">
           <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
               <div class="sb-sidenav-menu">
                   <div class="nav">

                       <a class="nav-link pt-4" href="index.html">
                           <div class="sb-nav-link-icon"><i class="fas fa-store"></i></div>
                           Store
                       </a>
                       <hr>

                       <a class="nav-link" href="index.html">
                           <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                           Home
                       </a>

                       @canany(['view-home-box', 'view-product', 'view-product-category', 'view-brand', 'view-color',
                           'view-size', 'view-product-attribute', 'manage-product-comments'])
                           <div class="sb-sidenav-menu-heading">Market</div>

                           <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                               data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                               <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                               Showcase
                               <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                           </a>

                           <div class="collapse" id="collapseLayouts" data-bs-parent="#sidenavAccordion">
                               <nav class="sb-sidenav-menu-nested nav">

                                   @can('view-product')
                                       <a class="nav-link" href="{{ route('admin.market.product.index') }}">Products</a>
                                   @endcan
                                   @can('view-product-category')
                                       <a class="nav-link" href="{{ route('admin.market.category.index') }}">Categories</a>
                                   @endcan

                                   @can('view-brand')
                                       <a class="nav-link" href="{{ route('admin.market.brand.index') }}">Brands</a>
                                   @endcan

                                   @can('view-color')
                                       <a class="nav-link" href="{{ route('admin.market.color.index') }}">Colors</a>
                                   @endcan

                                   @can('view-size')
                                       <a class="nav-link" href="{{ route('admin.market.size.index') }}">Sizes</a>
                                   @endcan

                                   @can('view-product-attribute')
                                       <a class="nav-link" href="{{ route('admin.market.property.index') }}">Product
                                           Attribute</a>
                                   @endcan

                                   @can('view-home-box')
                                       <a class="nav-link" href="{{ route('admin.market.home-box.index') }}">Home Boxes</a>
                                   @endcan

                                   @can('manage-product-comments')
                                       <a class="nav-link" href="{{ route('admin.market.comment.index') }}">Comments</a>
                                   @endcan

                               </nav>
                           </div>
                       @endcanany

                       @canany(['view-warehouse', 'view-warehouse-transaction'])
                           <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                               data-bs-target="#warehouseMenu" aria-expanded="false" aria-controls="warehouseMenu"
                               title="Manage all warehouses in the system">
                               <div class="sb-nav-link-icon"><i class="fas fa-warehouse"></i></div>
                               Warehouse
                               <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                           </a>
                           <div class="collapse" id="warehouseMenu" data-bs-parent="#collapseLayouts">
                               <nav class="sb-sidenav-menu-nested nav">

                                   @can('view-warehouse')
                                       <a class="nav-link" href="{{ route('admin.market.warehouse.index') }}">Warehouses
                                           List</a>
                                   @endcan
                                   @can('view-warehouse-transaction')
                                       <a class="nav-link" href="{{ route('admin.market.transaction.index') }}">Transactions</a>
                                   @endcan

                               </nav>
                           </div>
                       @endcanany

                       @can('manage-orders')
                           <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                               data-bs-target="#orderMenu" aria-expanded="false" aria-controls="orderMenu"
                               title="Manage all orders in the system">
                               <div class="sb-nav-link-icon"><i class="fa-solid fa-shopping-cart"></i></div>
                               Orders
                               <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                           </a>
                           <div class="collapse" id="orderMenu" data-bs-parent="#collapseLayouts">
                               <nav class="sb-sidenav-menu-nested nav">
                                   <a class="nav-link" href="{{ route('admin.market.order.index') }}">All Orders</a>
                                   <a class="nav-link" href="{{ route('admin.market.order.newOrder') }}">New</a>
                                   <a class="nav-link" href="{{ route('admin.market.order.sending') }}">Sending</a>
                                   <a class="nav-link" href="{{ route('admin.market.order.unpaid') }}">Unpaid</a>
                                   <a class="nav-link" href="{{ route('admin.market.order.canceled') }}">Canceled</a>
                                   <a class="nav-link" href="{{ route('admin.market.order.returned') }}">Returned</a>
                               </nav>
                           </div>
                       @endcan

                       @can('manage-payments')
                           <a class="nav-link" href="{{ route('admin.market.payment.index') }}">
                               <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                               Payments
                           </a>
                       @endcan

                       @canany(['view-coupon', 'view-common-discount', 'view-amazing-sale'])
                           <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                               data-bs-target="#discountMenu" aria-expanded="false" aria-controls="discountMenu"
                               title="Manage all discounts in the system">
                               <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                               Discounts
                               <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                           </a>
                           <div class="collapse" id="discountMenu" data-bs-parent="#collapseLayouts">
                               <nav class="sb-sidenav-menu-nested nav">
                                   @can('view-coupon')
                                       <a class="nav-link" href="{{ route('admin.market.discount.coupon') }}">Coupan
                                           Discount</a>
                                   @endcan

                                   @can('view-common-discount')
                                       <a class="nav-link" href="{{ route('admin.market.discount.common_discount') }}">Common
                                           Discount</a>
                                   @endcan

                                   @can('view-amazing-sale')
                                       <a class="nav-link" href="{{ route('admin.market.discount.amazingSale') }}">Amazing
                                           Sale</a>
                                   @endcan

                               </nav>
                           </div>
                       @endcanany

                       @can('view-delivery')
                           <a class="nav-link" href="{{ route('admin.market.delivery.index') }}">
                               <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                               Deliveries
                           </a>
                       @endcan

                       @canany(['view-post', 'view-post-category', 'view-tag', 'view-menu', 'view-faq', 'view-banner',
                           'manage-post-comments'])
                           <div class="sb-sidenav-menu-heading">Content</div>

                           @can('view-post')
                               <a class="nav-link" href="{{ route('admin.content.post.index') }}">
                                   <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                                   Posts
                               </a>
                           @endcan

                           @can('view-post-category')
                               <a class="nav-link" href="{{ route('admin.content.category.index') }}">
                                   <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                                   Post Catgeories
                               </a>
                           @endcan

                           @can('view-tag')
                               <a class="nav-link" href="{{ route('admin.content.tag.index') }}">
                                   <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                                   Tags
                               </a>
                           @endcan

                           @can('view-menu')
                               <a class="nav-link" href="{{ route('admin.content.menu.index') }}">
                                   <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                                   Menus
                               </a>
                           @endcan

                           @can('manage-post-comments')
                               <a class="nav-link" href="{{ route('admin.content.comment.index') }}">
                                   <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                                   Comments
                               </a>
                           @endcan

                           @can('view-faq')
                               <a class="nav-link" href="{{ route('admin.content.faq.index') }}">
                                   <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                                   FAQ
                               </a>
                           @endcan

                           @can('view-banner')
                               <a class="nav-link" href="{{ route('admin.content.banner.index') }}">
                                   <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                                   Banners
                               </a>
                           @endcan

                       @endcanany

                       @if (auth()->user()?->is_owner)
                           <div class="sb-sidenav-menu-heading">User</div>

                           <a class="nav-link" href="{{ route('admin.user.customer.index') }}">
                               <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                               Customers
                           </a>
                           <a class="nav-link" href="{{ route('admin.user.admin.index') }}">
                               <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                               Admins
                           </a>
                           <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                               data-bs-target="#AccessLevelsMenu" aria-expanded="false"
                               aria-controls="AccessLevelsMenu" title="Manage Access in the system">
                               <div class="sb-nav-link-icon"><i class="fa-solid fa-user-shield"></i></div>
                               Access levels
                               <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                           </a>
                           <div class="collapse" id="AccessLevelsMenu" data-bs-parent="#collapseLayouts">
                               <nav class="sb-sidenav-menu-nested nav">
                                   <a class="nav-link"
                                       href="{{ route('admin.user.permission.index') }}">Permission</a>
                                   <a class="nav-link" href="{{ route('admin.user.role.index') }}">Roles</a>
                               </nav>
                           </div>
                       @endif

                       @can('manage-tickets')
                           <div class="sb-sidenav-menu-heading">Tickets</div>

                           <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                               data-bs-target="#ticketMenu" aria-expanded="false" aria-controls="ticketMenu"
                               title="Manage all tickets in the system">
                               <div class="sb-nav-link-icon"><i class="fa-solid fa-headset"></i></div>
                               Tickets
                               <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                           </a>
                           <div class="collapse" id="ticketMenu" data-bs-parent="#collapseLayouts">
                               <nav class="sb-sidenav-menu-nested nav">
                                   <a class="nav-link" href="{{ route('admin.ticket.index') }}">All Tickets</a>
                                   <a class="nav-link" href="{{ route('admin.ticket.category.index') }}">Categories</a>
                                   <a class="nav-link" href="{{ route('admin.ticket.priority.index') }}">Priorities</a>
                                   <a class="nav-link" href="{{ route('admin.ticket.admin.index') }}">Admins</a>
                               </nav>
                           </div>
                       @endcan

                       @if (auth()->user()?->is_owner)
                           <div class="sb-sidenav-menu-heading">Setting</div>
                           <a class="nav-link" href="{{ route('admin.setting.index') }}">
                               <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                               Settings
                           </a>
                       @endif

                   </div>
               </div>
               <div class="sb-sidenav-footer">
                   <div class="small">Logged in as:</div>
                   {{ auth()->user()->full_name }}
               </div>
           </nav>
       </div>
