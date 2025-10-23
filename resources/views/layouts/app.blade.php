{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/jdm_distributors_logo.jpeg')  }}">

    <title>{{ config('app.name', 'JDM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Styles -->
     <!-- DataTables Bootstrap 5 CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        header {
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .content-wrapper {
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>
    <div class="min-vh-100 d-flex flex-column bg-light">

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="shadow-sm py-3 mb-3">
                <div class="container">
                    {{ $header }}
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
            <small class="text-muted">&copy; {{ date('Y') }} JDM Distributors. All rights reserved.</small>
        </footer>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
// <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    // <!-- DataTables core + Bootstrap 5 integration -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });
});
   
</script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#myTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[0, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products..."
                }
            });

            // Auto-hide alert
            const alert = document.querySelector('.alert-success');
            if(alert){
                setTimeout(() => new bootstrap.Alert(alert).close(), 4000);
            }
        });
    </script>
@stack('scripts')
</body>
</html> --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/jdm_distributors_logo.jpeg') }}">

    <title>{{ config('app.name', 'JDM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        header {
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .content-wrapper {
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>
    <div class="min-vh-100 d-flex flex-column bg-light">

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="shadow-sm py-3 mb-3">
                <div class="container">
                    {{ $header }}
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
            <small class="text-muted">&copy; {{ date('Y') }} JDM Distributors. All rights reserved.</small>
        </footer>
    </div>

   <!-- jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>



    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%'
            });

            // Initialize DataTable
            $('#myTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[0, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products..."
                }
            });

            // Auto-hide alert
            const alert = document.querySelector('.alert-success');
            if(alert){
                setTimeout(() => new bootstrap.Alert(alert).close(), 4000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
