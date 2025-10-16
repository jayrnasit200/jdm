<!DOCTYPE html>
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

    <!-- Custom Styles -->
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
</body>
</html>
