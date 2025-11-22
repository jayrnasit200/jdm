<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/jdm_distributors_logo.jpeg') }}">

    <title>{{ sys_config('site_name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css"
          rel="stylesheet">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
        }

        /* ==== Header (page heading area) with US flag gradient ==== */
        header {
            background-image:
                linear-gradient(90deg,
                    rgba(11, 60, 93, 0.75),
                    rgba(243, 82, 82, 0.75)
                ),
                url("https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
        }

        header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .page-heading-title {
            font-size: 1.35rem;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .page-heading-subtitle {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .content-wrapper {
            padding: 2rem 1rem;
        }

        footer {
            font-size: 0.8rem;
        }

        /* Optional: subtle background for the whole app */
        .min-vh-100 {
            background: radial-gradient(circle at top, rgba(15,23,42,0.12), transparent 50%);
        }
    </style>
</head>
<body>
    <div class="min-vh-100 d-flex flex-column bg-light">

        {{-- Top Navigation (unchanged, from your partial) --}}
        @include('layouts.navigation')

        {{-- Page Heading (we style this with the US flag gradient) --}}
        @isset($header)
            <header class="shadow-sm py-3 mb-3">
                <div class="container">
                    <div class="flex-grow-1">
                        {{-- You can optionally wrap your $header content in a styled div --}}
                        <div class="page-heading-title">
                            {{-- If $header is a simple <h2>, it will render here --}}
                            {{ $header }}
                        </div>
                    </div>
                    <div class="d-none d-md-flex align-items-center gap-2">
                        <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}"
                             alt="JDM Distributors"
                             style="width: 42px; height: 42px; border-radius: 999px; background:#fff; padding:4px; box-shadow:0 4px 10px rgba(15,23,42,0.40); object-fit:contain;">
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow-1 content-wrapper">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center py-3 mt-auto bg-white border-top">
            <small class="text-muted">
                &copy; {{ date('Y') }} JDM Distributors. All rights reserved.
            </small>
        </footer>
    </div>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Lightbox options
        lightbox.option({
            resizeDuration: 200,
            wrapAround: true,
            alwaysShowNavOnTouchDevices: true,
            albumLabel: "Image %1 of %2"
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%'
            });

            // Initialize DataTable (for pages that have #myTable)
            if ($('#myTable').length) {
                $('#myTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    order: [[0, 'asc']],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    }
                });
            }

            // Auto-hide success alert
            const alertEl = document.querySelector('.alert-success');
            if(alertEl){
                setTimeout(() => new bootstrap.Alert(alertEl).close(), 4000);
            }
        });
    </script>

    @stack('scripts')

    <!-- 🌟 Page Loader -->
    <div id="page-loader" style="
        position: fixed;
        inset: 0;
        background: rgba(255,255,255,0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
        <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            const loader = document.getElementById('page-loader');
            if(loader){
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
            }
        });
    </script>
</body>
</html>
