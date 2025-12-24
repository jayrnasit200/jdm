<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('seller.dashboard') }}">
            {{ sys_config('site_name') }}
        </a>

        <!-- Hamburger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            @auth
            @php $perm = Auth::user()->permission ?? null; @endphp

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- DASHBOARD - always visible --}}
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('seller.dashboard')) active @endif"
                       href="{{ route('seller.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                {{-- SHOPS --}}
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('shop.index')) active @endif"
                       href="{{ route('shop.index') }}">
                        Shops
                    </a>
                </li>

                {{-- PRODUCTS --}}
                @if($perm && $perm->product == 1)
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('products.index')) active @endif"
                       href="{{ route('products.index') }}">
                        Products
                    </a>
                </li>
                @endif

                {{-- CATEGORY --}}
                @if($perm && $perm->category == 1)
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('category')) active @endif"
                       href="{{ route('category') }}">
                        Categories
                    </a>
                </li>
                @endif

                {{-- DISCOUNT --}}
                @if($perm && $perm->discount == 1)
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('seller.shop-prices.*')) active @endif"
                       href="{{ route('seller.shop-prices.create') }}">
                        Shop Product Prices
                    </a>
                </li>
                @endif

            </ul>

            <!-- User Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="#" role="button" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                Profile
                            </a>
                        </li>

                        {{-- Shops in dropdown --}}
                        @if($perm && $perm->shop == 1)
                        <li>
                            <a class="dropdown-item" href="{{ route('shops.index') }}">
                                Shops
                            </a>
                        </li>
                        @endif

                        {{-- Products in dropdown --}}
                        @if($perm && $perm->products == 1)
                        <li>
                            <a class="dropdown-item" href="{{ route('products.index') }}">
                                Products
                            </a>
                        </li>
                        @endif

                        {{-- Categories in dropdown --}}
                        @if($perm && $perm->categories == 1)
                        <li>
                            <a class="dropdown-item" href="{{ route('category') }}">
                                Categories
                            </a>
                        </li>
                        @endif

                        {{-- Discount --}}
                        @if($perm && $perm->discounts == 1)
                        <li>
                            <a class="dropdown-item" href="{{ route('seller.shop-prices.create') }}">
                                Shop Product Prices
                            </a>
                        </li>
                        @endif

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </li>

                    </ul>
                </li>
            </ul>

            @endauth

            @guest
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            </ul>
            @endguest

        </div>
    </div>
</nav>
