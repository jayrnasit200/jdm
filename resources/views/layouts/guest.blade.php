<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/jdm_distributors_logo.jpeg')  }}">

    <title>{{ config('app.name', 'JDM') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa; /* Light gray background */
        }
        .auth-card {
            border-radius: 10px;
        }
        .logo-wrapper img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <!-- Logo -->
        <div class="logo-wrapper mb-4 text-center">
            <a href="{{ url('/') }}">
                {{-- Replace with your actual logo image --}}
                {{-- <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}" alt="JDM Logo"> --}}
            </a>
        </div>

        <!-- Auth Card -->
        <div class="card shadow-sm auth-card w-100" style="max-width: 420px;">
                {{ $slot }}
        </div>

        <!-- Optional Footer -->
        <div class="text-center mt-3 text-muted">
            &copy; {{ date('Y') }} JDM. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
