<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Under Development</title>
    <style>
        body {
            /* background: #0f172a; */
            color: #e2e8f0;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .logo {
            width: 500px;
            height: auto;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 2rem;
            margin: 10px 0;
        }
        p {
            font-size: 1.1rem;
            color: #94a3b8;
            margin-bottom: 25px;
        }
        a {
            padding: 10px 18px;
            background: #3b82f6;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s ease-in-out;
        }
        a:hover {
            background: #2563eb;
        }
        footer {
            position: absolute;
            bottom: 15px;
            font-size: 0.9rem;
            color: #64748b;
        }
    </style>
</head>
<body>
    <img src="{{ asset('assets/JDMLOGO.avif') }}" alt="JDM Logo" class="logo">
    <h1>🚧 Website Under Development 🚧</h1>
    <p>We’re currently building something awesome. Please check back soon!</p>
    <a href="https://jdmdistributors.co.uk" target="_blank">Visit Main Site</a>

    <footer>© {{ date('Y') }} JDM Distributors. All rights reserved.</footer>
</body>
</html>
