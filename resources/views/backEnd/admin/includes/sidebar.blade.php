@php
    $orders = activeThemeData()->orders()->where('status', 1)->count();
    $setting = \App\WebSettings::select('stock_management')->first();
    $abandoned_carts = activeThemeData()->ab_orders()->count();
@endphp

<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark sidebar" id="sidebar" data-scroll-save="true">

    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('admin.home') }}">
                {{ env('APP_NAME') }}
                {{-- <img src="{{asset('backEnd/assets/static/logo-white.svg')}}" width="110" height="32" alt="Tabler" class="navbar-brand-image"> --}}
            </a>
        </h1>
        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    {{-- <span class="avatar avatar-sm"
                        style="background-image: url({{ asset('backEnd/assets/static/avatars/default_avatar.jpg') }})"></span> --}}
                    <div class="d-xl-block ps-2">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-muted text-capitalize">
                            {{ Auth::guard('admin')->check() ? 'Admin' : (Auth::guard('admin')->check() ? 'Manager' : 'Employee') }}
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    {{-- <a href="{{ route('admin.profile.edit') }}" class="dropdown-item"><i class="icon ti ti-edit"></i>
                        &nbsp;Edit Profile</a>
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item"><i class="icon ti ti-edit"></i>
                        &nbsp;Edit Profile</a> --}}
                    <a href="javascript:void(0);" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="icon ti ti-logout"></i> &nbsp;Logout
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
                {{-- @if (Auth::guard('admin')->check()) --}}
                {{-- Dashboard --}}
                @can('dashboard.view')
                    <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.home') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-layout-dashboard
">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M9 3a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 12a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2zm10 -4a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 -8a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2z" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>
                @endcan

                {{-- Products  --}}
                @can('products.list')
                    <li class="nav-item {{ request()->is('admin-product*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.product') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-package">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 16v-8h2.5a2.5 2.5 0 1 1 0 5h-2.5" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Products</span>
                        </a>
                    </li>
                @endcan


                {{-- combo products --}}
                @can('products.combo.list')
                    <li class="nav-item {{ request()->is('admin/combo-product*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.combo-products.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-brand-producthunt">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 16v-8h2.5a2.5 2.5 0 1 1 0 5h-2.5" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Combo Products</span>
                        </a>
                    </li>
                @endcan

                {{-- @can('coupons.list') --}}
                <li class="nav-item {{ request()->is('admin/coupons*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ticket"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 5h14v14h-14z" />
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                        </span>
                        <span class="nav-link-title">Coupons</span>
                    </a>
                </li>
                {{-- @endcan --}}

                {{-- @can('flash_deals.list') --}}
                <li class="nav-item {{ request()->is('admin/flash-deal*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.flash.deals.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-flame"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 3c0 3 -4 4 -4 9a4 4 0 0 0 8 0c0 -5 -4 -6 -4 -9z" />
                            </svg>
                        </span>
                        <span class="nav-link-title">Flash Deals</span>
                    </a>
                </li>
                {{-- @endcan --}}

                {{-- Categories  --}}
                @can('categories.list')
                    <li class="nav-item {{ request()->is('admin-category*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.category') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-sitemap">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M3 15m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path
                                        d="M15 15m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path
                                        d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M6 15v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" />
                                    <path d="M12 9l0 3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Categories</span>
                        </a>
                    </li>
                @endcan

                {{-- Orders --}}
                @can('orders.list')
                    <li class="nav-item {{ request()->is('admin-orders*') ? 'active' : '' }}">
                        <a class="nav-link position-relative" href="{{ route('admin.orders') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 17h-11v-14h-2" />
                                    <path d="M6 5l14 1l-1 7h-13" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Orders</span>
                            @if ($orders > 0)
                                <span class="badge ord-badge-red">{{ $orders }}</span>
                            @endif
                        </a>
                    </li>
                @endcan


                {{-- abandoned cart --}}
                @can('abandoned.cart.list')
                    <li class="nav-item {{ request()->is('admin/abandoned-cart') ? 'active' : '' }}">
                        <a class="nav-link position-relative" href="{{ route('admin.abandoned.cart') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 17h-11v-14h-2" />
                                    <path d="M6 5l14 1l-1 7h-13" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Incomplete Orders</span>
                            @if ($abandoned_carts > 0)
                                <span class="badge inc-custom-badge">{{ $abandoned_carts }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

                {{-- Customers --}}
                @can('customers.list')
                    <li class="nav-item {{ request()->is('admin-customers*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.customers') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Customers</span>
                        </a>
                    </li>
                @endcan

                {{-- Sliders  --}}
                @can('sliders.list')
                    <li class="nav-item {{ request()->is('admin-slider*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.sliders') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-slideshow">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 6l.01 0" />
                                    <path
                                        d="M3 3m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                    <path d="M3 13l4 -4a3 5 0 0 1 3 0l4 4" />
                                    <path d="M13 12l2 -2a3 5 0 0 1 3 0l3 3" />
                                    <path d="M8 21l.01 0" />
                                    <path d="M12 21l.01 0" />
                                    <path d="M16 21l.01 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Sliders</span>
                        </a>
                    </li>
                @endcan


                {{-- Media  --}}
                @can('media.list')
                    <li class="nav-item {{ request()->is('admin-media*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.media') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-folders">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M9 3h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                    <path d="M17 16v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Media</span>
                        </a>
                    </li>
                @endcan

                {{-- Promotional Banner  --}}
                @can('promotion_banner.list')
                    <li class="nav-item {{ request()->is('admin-promotional-banner*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.promotional.banner.edit') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-battery-automotive">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                    <path d="M6 5v-2" />
                                    <path d="M18 3v2" />
                                    <path d="M6.5 12h3" />
                                    <path d="M14.5 12h3" />
                                    <path d="M16 10.5v3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Promotional Banner</span>
                        </a>
                    </li>
                @endcan

                {{-- Shipping Method  --}}
                @can('shipping_method.list')
                    <li class="nav-item {{ request()->is('admin-shipping_methods*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.shipping_methods') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-truck-delivery">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                                    <path d="M3 9l4 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Shipping Methods</span>
                        </a>
                    </li>
                @endcan

                {{-- visitor --}}
                @can('visitors.list')
                    <li class="nav-item {{ request()->is('admin-visitor*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.visitor.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-message-user">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M13 18l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
                                    <path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Visitors</span>
                        </a>
                    </li>
                @endcan

                {{-- ip address --}}
                @can('ip_address.list')
                    <li class="nav-item {{ request()->is('admin-ip-address*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.ip.address') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-map-pins">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10.828 9.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829z" />
                                    <path d="M8 7l0 .01" />
                                    <path d="M18.828 17.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829z" />
                                    <path d="M16 15l0 .01" />
                                </svg>
                            </span>
                            <span class="nav-link-title">IP Address</span>
                        </a>
                    </li>
                @endcan

                @can('device.list')
                    {{-- device --}}
                    <li class="nav-item {{ request()->is('admin/device*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.device') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-brand-chrome">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M12 9h8.4" />
                                    <path d="M14.598 13.5l-4.2 7.275" />
                                    <path d="M9.402 13.5l-4.2 -7.275" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Devices</span>
                        </a>
                    </li>
                @endcan


                <!-- role -->
                @if ($web_settings->is_demo == 0 || Auth::guard('admin')->user()->role_id == 1)
                    @can('roles.list')
                        <li class="nav-item {{ request()->is('admin/role*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.role.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-shield-lock">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                                        <path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        <path d="M12 12l0 2.5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Roles & Permissions</span>
                            </a>
                        </li>
                    @endcan
                @endif

                <!-- staff -->
                @if ($web_settings->is_demo == 0 || Auth::guard('admin')->user()->role_id == 1)
                    @can('staffs.list')
                        <li class="nav-item {{ request()->is('admin/staff*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.staff.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-square-rounded">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z" />
                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                        <path d="M6 20.05v-.05a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v.05" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Staffs</span>
                            </a>
                        </li>
                    @endcan
                @endif

                @can('landing.page.list')
                    <!-- landing -->
                    <li class="nav-item dropdown {{ request()->is('admin/landing*') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle {{ request()->is('admin/landing*') ? 'show' : '' }}"
                            href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                            aria-expanded="true">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-analytics">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M9 17l0 -5" />
                                    <path d="M12 17l0 -1" />
                                    <path d="M15 17l0 -3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Landing Pages
                            </span>
                        </a>
                        <div class="dropdown-menu {{ request()->is('admin/landing*') ? 'show' : '' }}"
                            data-bs-popper="none">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <!-- Landing Page -->
                                    <a class="dropdown-item {{ request()->is('admin/landing/pages') ? 'active' : '' }}"
                                        href="{{ route('admin.landing.pages.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="4"></circle>
                                        </svg>&nbsp;Landing Page
                                    </a>

                                    <!-- Manage Category -->
                                    <a class="dropdown-item {{ request()->is('admin/landing/category') ? 'active' : '' }}"
                                        href="{{ route('admin.landing.category.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="4"></circle>
                                        </svg>&nbsp;Manage Category
                                    </a>

                                    <!-- Manage Theme -->
                                    <a class="dropdown-item {{ request()->is('admin/landing/theme') ? 'active' : '' }}"
                                        href="{{ route('admin.landing.theme.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="4"></circle>
                                        </svg>&nbsp;Manage Theme
                                    </a>

                                </div>
                            </div>
                        </div>
                    </li>
                @endcan

                @can('review.list')
                    <li class="nav-item {{ request()->is('admin/review*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.reviews') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-star">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M12 17.25l-4.69 2.76l1.09 -6.32l-3.59 -3.5l6.29 -.91l2.81 -5.69l2.81 5.69l6.29 .91l-3.59 3.5l1.09 6.32l-4.69 -2.76">
                                    </path>
                                </svg>
                            </span>
                            <span class="nav-link-title">Reviews</span>
                        </a>
                    </li>
                @endcan

                {{-- Couriers  --}}
                @can('couriers.list')
                    <li class="nav-item {{ request()->is('admin-courier*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.courier') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-bike">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M19 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M12 19l0 -4l-3 -3l5 -4l2 3l3 0" />
                                    <path d="M17 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Couriers</span>
                        </a>
                    </li>
                @endcan

                @can('newsletter.list')
                    <li class="nav-item {{ request()->is('admin-newsletter*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.newsletter') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <polyline points="3 7 12 13 21 7" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Newsletter</span>
                        </a>
                    </li>
                @endcan

                @if ($web_settings->is_demo == 0 || Auth::guard('admin')->user()->role_id == 1)
                    @can('theme.list')
                        <li class="nav-item {{ request()->is('admin/themes') ? 'active' : '' }}">
                            {{-- @dd(request()->url()) --}}
                            <a class="nav-link" href="{{ route('admin.themes.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-screen-share">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="3" y="4" width="18" height="14" rx="2" />
                                        <path d="M7 20h10M12 16v4" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Themes</span>
                            </a>
                        </li>
                    @endcan
                @endif

                @can('marketing.list')
                    <li class="nav-item {{ request()->routeIs('admin.marketing.api') ? 'active' : '' }}">
                        {{-- @dd(request()->url()) --}}
                        {{-- conversion Api --}}
                        {{-- @can('marketing') --}}
                        <a class="nav-link" href="{{ route('admin.marketing.api') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-target"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="9" />
                                    <circle cx="12" cy="12" r="5" />
                                    <circle cx="12" cy="12" r="1" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Marketing</span>

                        </a>
                        {{-- @endcan --}}
                    </li>
                @endcan


                {{-- Manage stocks  --}}
                @canany(['purchases.list', 'suppliers.list'])
                    @if ($setting?->stock_management == 1)
                        <li
                            class="nav-item dropdown {{ request()->is('admin-supplier') || request()->is('admin-purchase*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown"
                                data-bs-auto-close="true" role="button" aria-expanded="true">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                        <path
                                            d="M15 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                        <path
                                            d="M9 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                        <path d="M4 20h14" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Manage Stocks
                                </span>
                            </a>
                            <div
                                class="dropdown-menu {{ request()->is('admin-supplier') || request()->is('admin-purchase*') ? 'show' : '' }}">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        @can('suppliers.list')
                                            <a class="dropdown-item {{ request()->is('admin-supplier') ? 'active' : '' }}"
                                                href="{{ route('admin.supplier') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-point" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="12" cy="12" r="4"></circle>
                                                </svg>&nbsp;Suppliers
                                            </a>
                                        @endcan
                                        @can('purchases.list')
                                            <a class="dropdown-item {{ request()->is('admin-purchase*') ? 'active' : '' }}"
                                                href="{{ route('admin.purchase') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-point" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="12" cy="12" r="4"></circle>
                                                </svg>&nbsp;Purchase
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endcan

                <!-- finance -->
                @canany(['accounts.list', 'expenses.category.list', 'accounts.transaction.list',
                    'expenses.category.list'])
                    <li class="nav-item dropdown {{ request()->is('admin/account*') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle {{ request()->is('admin/account*') ? 'show' : '' }}"
                            href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                            aria-expanded="true">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-coins">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" />
                                    <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" />
                                    <path
                                        d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" />
                                    <path d="M3 6v10c0 .888 .772 1.45 2 2" />
                                    <path d="M3 11c0 .888 .772 1.45 2 2" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Finance
                            </span>
                        </a>
                        <div class="dropdown-menu {{ request()->is('admin/account*') || request()->is('admin/expense*') ? 'show' : '' }}"
                            data-bs-popper="none">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    @can('accounts.list')
                                        <!-- account -->
                                        <a class="dropdown-item {{ request()->is('admin/accounts') ? 'active' : '' }}"
                                            href="{{ route('admin.account.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Accounts
                                        </a>
                                    @endcan

                                    <!-- account transaction -->
                                    @can('accounts.transaction.list')
                                        <a class="dropdown-item {{ request()->is('admin/account-transactions') ? 'active' : '' }}"
                                            href="{{ route('admin.account.transaction.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Transactions
                                        </a>
                                    @endcan
                                    @canany(['expenses.list', 'expenses.category.list'])
                                        <div class="dropend">
                                            <a class="dropdown-item dropdown-toggle {{ request()->is('admin/expense*') ? 'show active' : '' }}"
                                                href="#sidebar-authentication" data-bs-toggle="dropdown"
                                                data-bs-auto-close="false" role="button" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-point" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="12" cy="12" r="4"></circle>
                                                </svg>&nbsp;Expenses
                                            </a>
                                            <div
                                                class="dropdown-menu {{ request()->is('admin/expense-categories') || request()->is('admin/expenses') ? 'show' : '' }}">
                                                @can('expenses.category.list')
                                                    <a class="dropdown-item {{ request()->is('admin/expense-categories') ? 'active' : '' }}"
                                                        href="{{ route('admin.expense.category.index') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-point" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <circle cx="12" cy="12" r="4"></circle>
                                                        </svg>&nbsp;Categories
                                                    </a>
                                                @endcan
                                                @can('expenses.list')
                                                    <a class="dropdown-item {{ request()->is('admin/expenses') ? 'active' : '' }}"
                                                        href="{{ route('admin.expense.index') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-point" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <circle cx="12" cy="12" r="4"></circle>
                                                        </svg>&nbsp;Expense List
                                                    </a>
                                                @endcan

                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </li>
                @endcan
                <!-- report -->
                @canany(['reports.profit.loss', 'reports.account.transaction', 'reports.product.stock',
                    'reports.courier', 'reports.courier'])
                    <li class="nav-item dropdown {{ request()->is('admin/report*') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle {{ request()->is('admin/report*') ? 'show' : '' }}"
                            href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                            aria-expanded="true">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-analytics">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M9 17l0 -5" />
                                    <path d="M12 17l0 -1" />
                                    <path d="M15 17l0 -3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Reports
                            </span>
                        </a>
                        <div class="dropdown-menu {{ request()->is('admin/report*') ? 'show' : '' }}"
                            data-bs-popper="none">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    @can('reports.sales')
                                        <a class="dropdown-item {{ request()->is('admin/report-sales-orders') ? 'active' : '' }}"
                                            href="{{ route('admin.report.sales.order') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Sales (Order-Wise)
                                        </a>
                                    @endcan
                                    @can('reports.sales')
                                        <a class="dropdown-item {{ request()->is('admin/report-sales-products') ? 'active' : '' }}"
                                            href="{{ route('admin.report.sales.product') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Sales (Product-Wise)
                                        </a>
                                    @endcan

                                    <!-- profit/loss -->
                                    @can('reports.profit.loss')
                                        <a class="dropdown-item {{ request()->is('admin/report-profit-loss') ? 'active' : '' }}"
                                            href="{{ route('admin.report.profit.loss') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Profit/Loss
                                        </a>
                                    @endcan
                                    <!-- account transaction -->
                                    @can('reports.account.transaction')
                                        <a class="dropdown-item {{ request()->is('admin/report-account-trans') ? 'active' : '' }}"
                                            href="{{ route('admin.report.account.trans') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;ACC Trans.
                                        </a>
                                    @endcan
                                    <!-- product stock -->
                                    @can('reports.product.stock')
                                        <a class="dropdown-item {{ request()->is('admin/report-product-stock') ? 'active' : '' }}"
                                            href="{{ route('admin.report.product.stock') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Product Stocks
                                        </a>
                                    @endcan
                                    <!-- courier -->
                                    @can('reports.courier')
                                        <a class="dropdown-item {{ request()->is('admin/report-courier') ? 'active' : '' }}"
                                            href="{{ route('admin.report.courier') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Couriers
                                        </a>
                                    @endcan
                                    @can('reports.products')
                                        <!-- product -->
                                        <a class="dropdown-item {{ request()->is('admin/report-product') ? 'active' : '' }}"
                                            href="{{ route('admin.report.product') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Products
                                        </a>
                                    @endcan
                                    <!-- employee -->
                                    {{-- <a class="dropdown-item {{ request()->is('admin/report-employee') ? 'active' : '' }}"
                                    href="{{ route('admin.report.employee') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="4"></circle>
                                    </svg>&nbsp;Employees
                                </a> --}}
                                </div>
                            </div>
                        </div>
                    </li>
                @endcan





                @canany(['settings.general', 'settings.page', 'settings.courier_api'])
                    <li
                        class="nav-item dropdown {{ request()->is('admin-settings*') || request()->is('admin-api*') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="false" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                    </path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Settings
                            </span>
                        </a>
                        <div
                            class="dropdown-menu {{ request()->is('admin-settings*') || request()->is('admin-api*') ? 'show' : '' }}">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    @can('settings.general')
                                        <a class="dropdown-item {{ request()->is('admin-settings-web') ? 'active' : '' }}"
                                            href="{{ route('admin.settings.web') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Web
                                        </a>
                                    @endcan
                                    @can('settings.page')
                                        <a class="dropdown-item {{ request()->is('admin-settings-page') ? 'active' : '' }}"
                                            href="{{ route('admin.settings.page') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Page
                                        </a>
                                    @endcan
                                    @can('settings.print')
                                        <a class="dropdown-item {{ request()->is('admin-settings-print') ? 'active' : '' }}"
                                            href="{{ route('admin.settings.print') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Print
                                        </a>
                                    @endcan
                                    @can('settings.home.page')
                                        <a class="dropdown-item {{ request()->is('admin-settings-home-page') ? 'active' : '' }}"
                                            href="{{ route('admin.settings.home.page') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Home Page
                                        </a>
                                    @endcan


                                    @can('settings.attribute')
                                        <a class="dropdown-item {{ request()->is('admin-settings-attribute') ? 'active' : '' }}"
                                            href="{{ route('admin.settings.attribute') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-point"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="12" cy="12" r="4"></circle>
                                            </svg>&nbsp;Attribute
                                        </a>
                                    @endcan
                                    @can('settings.courier_api')
                                        <div class="dropend">
                                            <a class="dropdown-item dropdown-toggle" href="javascript:void(0)"
                                                data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                                                aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-point" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="12" cy="12" r="4"></circle>
                                                </svg>&nbsp;
                                                Courier API
                                            </a>
                                            <div class="dropdown-menu {{ request()->is('admin-api*') ? 'show' : '' }}">
                                                <a href="{{ route('admin.api.pathao') }}"
                                                    class="dropdown-item {{ request()->is('admin-api-pathao') ? 'active' : '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-point" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="12" cy="12" r="4"></circle>
                                                    </svg>&nbsp;
                                                    Pathao
                                                </a>
                                                <a href="{{ route('admin.api.steadfast') }}"
                                                    class="dropdown-item {{ request()->is('admin-api-steadfast') ? 'active' : '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-point" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="12" cy="12" r="4"></circle>
                                                    </svg>&nbsp;
                                                    Stead Fast
                                                </a>
                                                <a href="{{ route('admin.api.redx') }} "
                                                    class="dropdown-item {{ request()->is('admin-api-redx') ? 'active' : '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-point" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="12" cy="12" r="4"></circle>
                                                    </svg>&nbsp;
                                                    Redx
                                                </a>
                                                <a href="{{ route('admin.api.carrybee') }} "
                                                    class="dropdown-item {{ request()->is('admin-api-carrybee') ? 'active' : '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-point" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="12" cy="12" r="4"></circle>
                                                    </svg>&nbsp;
                                                    Carrybee
                                                </a>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </li>
                @endcan


            </ul>
        </div>

    </div>
</aside>
