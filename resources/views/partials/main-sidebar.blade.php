        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="{{ asset('dist/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">Restaurant</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item menu-open">
                        <li class="nav-header">General</li>

                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link ">
                                <i class="bi bi-speedometer2 me-1"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('create-staff') }}" class="nav-link">
                                <i class="bi bi-person-plus me-1"></i>
                                <p>
                                    Create Staff
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders_page') }}" class="nav-link">
                                <i class="bi bi-receipt me-1"></i>
                                <p>
                                    Orders
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('billing_page') }}" class="nav-link">
                                <i class="bi bi-cash-coin me-1"></i>
                                <p>
                                    Billings
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kitchen') }}" class="nav-link">
                                <i class="bi bi-fire me-1"></i>
                                <p>
                                    Kitchen View
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('section.index') }}" class="nav-link">
                                <i class="bi bi-collection"></i>
                                <p>
                                    Sections
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link">
                                <i class="bi bi-grid"></i>
                                <p>
                                    Categories
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subcategory.index') }}" class="nav-link">
                                <i class="bi bi-diagram-3"></i>
                                <p>
                                    SubCategories
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('item.index') }}" class="nav-link">
                                <i class="bi bi-bag"></i>
                                <p>
                                    Items
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('menu') }}" class="nav-link">
                                <i class="bi bi-book-half me-1"></i>
                                <p>
                                    Menu View
                                </p>
                            </a>
                        </li>


                    </ul>
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
