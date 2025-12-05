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

                       <div class="sb-sidenav-menu-heading">Market</div>

                       <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                           data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                           <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                           Showcase
                           <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                       </a>
                       <div class="collapse" id="collapseLayouts" data-bs-parent="#sidenavAccordion">
                           <nav class="sb-sidenav-menu-nested nav">
                               <a class="nav-link" href="{{ route('admin.market.category.index') }}">Categories</a>
                               <a class="nav-link" href="{{ route('admin.market.brand.index') }}">Brands</a>
                               <a class="nav-link" href="{{ route('admin.market.color.index') }}">Colors</a>
                               <a class="nav-link" href="{{ route('admin.market.size.index') }}">Sizes</a>
                               <a class="nav-link" href="{{ route('admin.market.product.index') }}">Products</a>
                                 <a class="nav-link" href="{{ route('admin.market.property.index') }}">Product
                                   Attribute</a>
                               <a class="nav-link" href="{{ route('admin.market.home-box.index') }}">Home Boxes</a>
                               <a class="nav-link" href="{{ route('admin.market.comment.index') }}">Comments</a>
                           </nav>
                       </div>

                       <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                           data-bs-target="#warehouseMenu" aria-expanded="false" aria-controls="warehouseMenu"
                           title="Manage all warehouses in the system">
                           <div class="sb-nav-link-icon"><i class="fas fa-warehouse"></i></div>
                           Warehouse
                           <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                       </a>
                       <div class="collapse" id="warehouseMenu" data-bs-parent="#collapseLayouts">
                           <nav class="sb-sidenav-menu-nested nav">
                               <a class="nav-link" href="{{ route('admin.market.warehouse.index') }}">Warehouses
                                   List</a>
                               <a class="nav-link" href="{{ route('admin.market.transaction.index') }}">Transactions</a>
                           </nav>
                       </div>

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

                       <a class="nav-link" href="{{ route('admin.market.payment.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                           Payments
                       </a>

                       <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                           data-bs-target="#discountMenu" aria-expanded="false" aria-controls="discountMenu"
                           title="Manage all discounts in the system">
                           <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                           Discounts
                           <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                       </a>
                       <div class="collapse" id="discountMenu" data-bs-parent="#collapseLayouts">
                           <nav class="sb-sidenav-menu-nested nav">
                               <a class="nav-link" href="{{ route('admin.market.discount.coupon') }}">Coupan
                                   Discount</a>
                               <a class="nav-link" href="{{ route('admin.market.discount.common_discount') }}">Common
                                   Discount</a>
                               <a class="nav-link" href="{{ route('admin.market.discount.amazingSale') }}">Amazing
                                   Sale</a>
                           </nav>
                       </div>


                       <a class="nav-link" href="{{ route('admin.market.delivery.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                           Deliveries
                       </a>

                       <div class="sb-sidenav-menu-heading">Content</div>


                       <a class="nav-link" href="{{ route('admin.content.post.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                           Posts
                       </a>
                       <a class="nav-link" href="{{ route('admin.content.category.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                           Post Catgeories
                       </a>
                       <a class="nav-link" href="{{ route('admin.content.menu.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                           Menus
                       </a>
                       <a class="nav-link" href="{{ route('admin.content.comment.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                           Comments
                       </a>
                       <a class="nav-link" href="{{ route('admin.content.faq.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                           FAQ
                       </a>

                       <a class="nav-link" href="{{ route('admin.content.banner.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>
                           Banners
                       </a>

                       <div class="sb-sidenav-menu-heading">User</div>

                       <a class="nav-link" href="{{ route('admin.user.customer.index') }}">
                           <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                           Customers
                       </a>



                       <div class="sb-sidenav-menu-heading">Tickets</div>

                       <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                           data-bs-target="#ticketMenu" aria-expanded="false" aria-controls="ticketMenu"
                           title="Manage all tickets in the system">
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

                       <div class="sb-sidenav-menu-heading">Users</div>
                       <a class="nav-link" href="charts.html">
                           <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                           Charts
                       </a>
                       <a class="nav-link" href="tables.html">
                           <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                           Tables
                       </a>


                   </div>
               </div>
               <div class="sb-sidenav-footer">
                   <div class="small">Logged in as:</div>
                   Start Bootstrap
               </div>
           </nav>
       </div>
