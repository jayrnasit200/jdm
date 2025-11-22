<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('seller.dashboard') }}">
            {{ sys_config('site_name') }}
        </a>

        <!-- Hamburger / Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('seller.dashboard')) active @endif" href="{{ route('seller.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <!-- Add more links here if needed -->
                {{-- <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('category')) active @endif" href="{{ route('customere') }}">
                        Catogorys
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('products.index')) active @endif" href="{{ route('products.index') }}">
                        Product
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('shop.index')) active @endif" href="{{ route('shop.index') }}">
                        shops
                    </a>
                </li>


            </ul>

            <!-- User Dropdown -->
            @auth
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                Profile
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('shops.index') }}">
                                shops
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('products.index') }}">
                                Products
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('category') }}">
                                Catogorys
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
            </ul>
            @endguest
        </div>
    </div>
</nav>
