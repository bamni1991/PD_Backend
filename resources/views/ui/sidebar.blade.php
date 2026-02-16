    <nav>
        <div class="app-logo">
            <a class="logo d-inline-block" href="index.html">
                <img alt="#" src="{{ asset('assets') }}/images/logo/1.png">
            </a>

            <span class="bg-light-primary toggle-semi-nav">
                <i class="ti ti-chevrons-right f-s-20"></i>
            </span>
        </div>
        <div class="app-nav" id="app-simple-bar">
            <ul class="main-nav p-0 mt-2">
                <li class="menu-title">
                    <span>Menu</span>
                </li>
                <li class="no-sub">
                    <a class="" href="{{ route('home') }}">
                        <i class="iconoir-home-alt"></i> Home
                    </a>
                </li>



                @if (Auth::user()->email == 'admin@school.com')
                    <li class="no-sub">
                        <a class="" href="{{ url('file-manager') }}">
                            <i class="iconoir-apple-shortcuts"></i> File Manager
                        </a>
                    </li>


                    <li>
                        <a aria-expanded="true" class="collapsed" data-bs-toggle="collapse" href="#apps">
                            <i class="iconoir-user"></i>
                            Personal Menu
                        </a>
                        <ul class="collapse" id="apps" style="">
                            <li><a href="{{ url('personal-diary') }}">Personal Diary</a></li>
                            {{-- <li class="another-level">
                            <a aria-expanded="false" class="collapsed" data-bs-toggle="collapse" href="#Profile-page">
                                Profile
                            </a>
                            <ul class="collapse" id="Profile-page" style="">
                                <li><a href="profile.html">Profile</a></li>
                                <li><a href="setting.html">Setting</a></li>
                            </ul>
                        </li>
                        <li class="another-level">
                            <a aria-expanded="false" class="collapsed" data-bs-toggle="collapse" href="#projects-page">
                                Projects Page
                            </a>
                            <ul class="collapse" id="projects-page" style="">
                                <li><a href="project_app.html">projects</a></li>
                                <li><a href="project_details.html">projects Details</a></li>
                            </ul>
                        </li>
                        <li><a href="to_do.html">To-Do</a></li>
                        <li><a href="team.html">Team</a></li>
                        <li><a href="api.html">API</a></li>
                        <li class="another-level">
                            <a aria-expanded="false" class="collapsed" data-bs-toggle="collapse" href="#ticket-page">
                                Ticket
                            </a>
                            <ul class="collapse" id="ticket-page" style="">
                                <li><a href="ticket.html">Ticket</a></li>
                                <li><a href="ticket_details.html">Ticket Details</a></li>
                            </ul>
                        </li>
                        <li class="another-level">
                            <a aria-expanded="false" class="collapsed" data-bs-toggle="collapse" href="#email-page">
                                Email Page
                            </a>
                            <ul class="collapse" id="email-page" style="">
                                <li><a href="email.html"> Email</a></li>
                                <li><a href="read_email.html">Read Email</a></li>
                            </ul>
                        </li>
                        <li class="another-level">
                            <a aria-expanded="false" class="collapsed" data-bs-toggle="collapse" href="#e-shop">
                                E-shop
                            </a>
                            <ul class="collapse" id="e-shop" style="">
                                <li><a href="cart.html">Cart</a></li>
                                <li><a href="product.html">Product</a></li>
                                <li><a href="add_product.html">Add Product</a></li>
                                <li><a href="product_details.html">Product-Details</a></li>
                                <li><a href="product_list.html">Product list</a></li>
                                <li><a href="orders.html">Orders</a></li>
                                <li><a href="orders_details.html">Orders Details</a></li>
                                <li><a href="orders_list.html">Orders List</a></li>
                                <li><a href="checkout.html">Check out</a></li>
                                <li><a href="wishlist.html">Wishlist</a></li>
                            </ul>
                        </li>
                        <li><a href="invoice.html">Invoice</a></li>
                        <li><a href="chat.html">Chat</a></li>
                        <li><a href="filemanager.html">File manager</a></li>
                        <li><a href="bookmark.html">Bookmark</a></li>
                        <li><a href="kanban_board.html">Kanban board</a></li>
                        <li><a href="timeline.html">Timeline</a></li>
                        <li><a href="faq.html">FAQS</a></li>
                        <li><a href="pricing.html">Pricing</a></li>
                        <li><a href="gallery.html">Gallery</a></li>
                        <li class="another-level">
                            <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#blog-page">
                                Blog Page
                            </a>
                            <ul class="collapse" id="blog-page">
                                <li><a href="blog.html">Blog</a></li>
                                <li><a href="blog_read_more.html">Blog Details</a></li>
                                <li><a href="add_blog.html">Add Blog</a></li>

                            </ul>
                        </li> --}}
                        </ul>
                    </li>
                @endif
            </ul>
        </div>

        <div class="menu-navs">
            <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
            <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
        </div>

    </nav>
