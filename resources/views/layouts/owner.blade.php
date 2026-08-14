<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/jdm_distributors_logo.jpeg') }}">

    <title>@yield('title', 'Owner Panel') - {{ sys_config('site_name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --owner-navy: #0b3c5d;
            --owner-ink: #12243b;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
        }

        .owner-wrapper {
            min-height: 100vh;
            display: flex;
            background: radial-gradient(circle at top, rgba(15,23,42,0.12), transparent 55%);
        }

        .owner-sidebar {
            width: 230px;
            background: linear-gradient(180deg, var(--owner-navy), var(--owner-ink));
            color: #e5e7eb;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .owner-sidebar .brand,
        .offcanvas-owner .brand {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .owner-sidebar .brand img,
        .offcanvas-owner .brand img,
        .mobile-header-brand img {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            object-fit: contain;
            background: #fff;
            padding: 3px;
        }

        .owner-nav .nav-link {
            color: #e5e7eb;
            font-size: 0.9rem;
            padding: 0.7rem 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            border-radius: 999px;
            margin: 0.18rem 0.75rem;
        }

        .owner-nav .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 0.95rem;
        }

        .owner-nav .nav-link:hover,
        .owner-nav .nav-link.active {
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

        .owner-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .owner-header {
            background-image: linear-gradient(90deg, rgba(11, 60, 93, 0.95), rgba(243, 82, 82, 0.88));
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.25);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .owner-header .container-fluid {
            padding: 0.65rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
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

        .owner-content {
            flex: 1;
            padding: 1.5rem 1.25rem 1.25rem;
        }

        footer {
            font-size: 0.75rem;
        }

        .card.shadow-soft {
            box-shadow: 0 6px 16px rgba(15,23,42,0.11);
            border-radius: 0.9rem;
        }

        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }

        .offcanvas-owner {
            background: linear-gradient(180deg, var(--owner-navy), var(--owner-ink));
            color: #e5e7eb;
            width: min(300px, 88vw);
        }

        .offcanvas-owner .btn-close {
            filter: invert(1);
        }

        .owner-menu-btn {
            width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .min-width-0 {
            min-width: 0;
        }

        .mobile-product-card img,
        .mobile-list-card img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
        }

        @media (max-width: 991.98px) {
            .owner-wrapper {
                flex-direction: column;
            }

            .owner-sidebar {
                display: none !important;
            }

            .owner-header .container-fluid {
                padding: 0.55rem 0.8rem;
            }

            .owner-page-subtitle {
                display: none;
            }

            .owner-page-title {
                font-size: 0.92rem;
                max-width: 46vw;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mobile-header-brand img {
                width: 32px;
                height: 32px;
            }

            .owner-content {
                padding: 1rem 0.8rem 1.25rem;
            }

            .owner-toolbar {
                flex-direction: column;
                align-items: stretch !important;
                gap: 0.65rem !important;
            }

            .owner-toolbar .btn {
                width: 100%;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: left !important;
                margin-bottom: 0.5rem;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0 !important;
            }

            .select2-container {
                width: 100% !important;
            }

            .h4, .fs-5 {
                word-break: break-word;
            }
        }

        @media (max-width: 575.98px) {
            .owner-kpi .h4 {
                font-size: 1.05rem;
            }
        }
    </style>

    @stack('head')
    @stack('styles')
</head>
<body>
<div class="owner-wrapper">

    <aside class="owner-sidebar d-none d-lg-flex">
        <div class="brand">
            <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}" alt="JDM">
            <div class="title">
                <div class="fw-semibold">Owner Panel</div>
                <div class="text-white-50 small">JDM Distributors</div>
            </div>
        </div>

        <nav class="owner-nav mt-2">
            @include('owner.partials.nav-links')
        </nav>

        <div class="owner-sidebar-footer">
            <div>Logged in as:</div>
            <div>{{ auth()->user()->name ?? 'Owner' }}</div>
        </div>
    </aside>

    <div class="offcanvas offcanvas-start offcanvas-owner" tabindex="-1" id="ownerMobileMenu">
        <div class="offcanvas-header brand mb-0">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}" alt="JDM">
                <div>
                    <div class="fw-semibold">Owner Panel</div>
                    <div class="text-white-50 small">{{ auth()->user()->name ?? 'Owner' }}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="owner-nav mt-2">
                @include('owner.partials.nav-links')
            </nav>
            <div class="owner-sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm w-100">
                        <i class="fa fa-sign-out"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="owner-main">
        <header class="owner-header">
            <div class="container-fluid">
                <div class="d-flex align-items-center gap-2 min-width-0">
                    <button class="btn btn-outline-light owner-menu-btn d-lg-none"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#ownerMobileMenu"
                            aria-controls="ownerMobileMenu"
                            aria-label="Open menu">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="d-lg-none mobile-header-brand">
                        <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}" alt="JDM">
                    </div>

                    <div class="min-width-0">
                        <div class="owner-page-title">
                            @yield('page_title', 'Owner Dashboard')
                        </div>
                        <div class="owner-page-subtitle">
                            @yield('page_subtitle', 'Monitor sellers, orders and sales performance in one place.')
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <div class="owner-user text-end d-none d-md-block">
                        <div class="small">{{ auth()->user()->name ?? 'Owner' }}</div>
                        <div class="text-white-50 small">Role: Owner</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="d-none d-lg-block">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">
                            <i class="fa fa-sign-out"></i> Logout
                        </button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}" class="d-lg-none">
                        @csrf
                        <button class="btn btn-outline-light owner-menu-btn" aria-label="Logout">
                            <i class="fa fa-sign-out"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
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
