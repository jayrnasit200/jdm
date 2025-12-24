<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/jdm_distributors_logo.jpeg') }}">

    <title>@yield('title', 'Owner Panel') - {{ sys_config('site_name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables CSS (optional for reports) -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
        }

        .owner-wrapper {
            min-height: 100vh;
            display: flex;
            background: radial-gradient(circle at top, rgba(15,23,42,0.12), transparent 55%);
        }

        /* Sidebar */
        .owner-sidebar {
            width: 230px;
            background: linear-gradient(180deg, #0b3c5d, #12243b);
            color: #e5e7eb;
            display: flex;
            flex-direction: column;
        }

        .owner-sidebar .brand {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .owner-sidebar .brand img {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            object-fit: contain;
            background: #fff;
            padding: 3px;
        }

        .owner-sidebar .brand .title {
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .owner-sidebar .nav-link {
            color: #e5e7eb;
            font-size: 0.85rem;
            padding: 0.6rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            border-radius: 999px;
            margin: 0.15rem 0.75rem;
        }

        .owner-sidebar .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
        }

        .owner-sidebar .nav-link:hover,
        .owner-sidebar .nav-link.active {
            background: rgba(255,255,255,0.13);
            color: #fff;
        }

        .owner-sidebar-footer {
            margin-top: auto;
            padding: 0.75rem 1.25rem;
            font-size: 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            opacity: 0.9;
        }

        /* Main */
        .owner-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .owner-header {
            background-image:
                linear-gradient(90deg,
                    rgba(11, 60, 93, 0.9),
                    rgba(243, 82, 82, 0.85)
                );
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.25);
        }

        .owner-header .container-fluid {
            padding: 0.6rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .owner-page-title {
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .owner-page-subtitle {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .owner-user {
            font-size: 0.8rem;
        }

        .owner-content {
            flex: 1;
            padding: 1.5rem 1.25rem 1.25rem;
        }

        footer {
            font-size: 0.75rem;
        }

        /* Mobile: sidebar becomes offcanvas */
        @media (max-width: 991.98px) {
            .owner-wrapper {
                flex-direction: column;
            }
            .owner-sidebar {
                display: none;
            }
            .owner-main {
                flex: unset;
            }

            .mobile-header-brand {
                display: flex;
                align-items: center;
                gap: 0.6rem;
            }
            .mobile-header-brand img {
                width: 32px;
                height: 32px;
                border-radius: 999px;
                object-fit: contain;
                background: #fff;
                padding: 2px;
            }
            .mobile-header-brand span {
                font-size: 0.8rem;
                font-weight: 500;
            }
        }

        .card.shadow-soft {
            box-shadow: 0 6px 16px rgba(15,23,42,0.11);
            border-radius: 0.9rem;
        }
    </style>

    @stack('head')
</head>
<body>
<div class="owner-wrapper">

    {{-- ❗ Desktop sidebar --}}
    <aside class="owner-sidebar d-none d-lg-flex">
        <div class="brand">
            <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}" alt="JDM">
            <div class="title">
                <div class="fw-semibold">Owner Panel</div>
                <div class="text-white-50 small">JDM Distributors</div>
            </div>
        </div>

        <nav class="mt-2">
            <a href="{{ route('owner.dashboard') }}"
               class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                <i class="fa fa-home"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('owner.sellers.index') }}"
               class="nav-link {{ request()->routeIs('owner.sellers.*') ? 'active' : '' }}">
                <i class="fa fa-users"></i> <span>Sellers</span>
            </a>
            <a href="{{ route('owner.reports.products') }}"
               class="nav-link {{ request()->routeIs('owner.reports.sales') ? 'active' : '' }}">
                <i class="fa fa-line-chart"></i> <span>Sales Reports</span>
            </a>
            <a href="{{ route('reports.shop-balance') }}"
   class="nav-link {{ request()->routeIs('reports.shop-balance') ? 'active' : '' }}">
    <i class="fa fa-building"></i> <span>Shop Balances</span>
</a>
{{-- <a href="{{ route('owner.reports.shop-balance') }}"
   class="nav-link {{ request()->routeIs('owner.reports.shop-balance') ? 'active' : '' }}">
    <i class="fa fa-building"></i> <span>Shop Balances</span>
</a> --}}

            <a href="{{ url('/') }}" class="nav-link">
                <i class="fa fa-external-link"></i> <span>Back To Site</span>
            </a>
        </nav>

        <div class="owner-sidebar-footer">
            <div>Logged in as:</div>
            <div>{{ auth()->user()->name ?? 'Owner' }}</div>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="owner-main">

        {{-- Top header --}}
        <header class="owner-header">
            <div class="container-fluid">
                <div class="d-flex align-items-center gap-2">
                    {{-- Mobile brand --}}
                    <div class="d-lg-none mobile-header-brand">
                        <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}" alt="JDM">
                        <span>Owner Panel</span>
                    </div>
                    <div>
                        <div class="owner-page-title">
                            @yield('page_title', 'Owner Dashboard')
                        </div>
                        <div class="owner-page-subtitle">
                            @yield('page_subtitle', 'Monitor sellers, orders and sales performance in one place.')
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="owner-user text-end d-none d-sm-block">
                        <div class="small">{{ auth()->user()->name ?? 'Owner' }}</div>
                        <div class="text-white-50 small">Role: Owner</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">
                            <i class="fa fa-sign-out"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="owner-content">
            @yield('content')
        </main>

        <footer class="text-center py-2 bg-white border-top">
            <small class="text-muted">
                &copy; {{ date('Y') }} JDM Distributors · Owner Panel
            </small>
        </footer>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        // Auto-hide success alert
        const alertEl = document.querySelector('.alert-success');
        if (alertEl) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertEl);
                bsAlert.close();
            }, 4000);
        }
    });
</script>

@stack('scripts')
</body>
</html>
