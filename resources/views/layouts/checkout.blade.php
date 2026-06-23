<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Checkout - Mondial Bakery')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #7a4b22;
            --primary-light: #c7834d;
            --primary-dark: #512c16;
            --secondary: #fff4e7;
            --accent: #f3bb67;
            --accent-soft: #ffe1bf;
            --bg: #fffaf5;
            --text-dark: #1f140d;
            --text-medium: #6c5243;
            --text-light: #9a816f;
            --white: #ffffff;
            --border: rgba(122, 75, 34, 0.12);
            --gradient-gold: linear-gradient(135deg, #7a4b22 0%, #c7834d 50%, #f3bb67 100%);
            --gradient-dark: linear-gradient(135deg, #1c120d 0%, #392116 55%, #6c3e1c 100%);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fdf6ed 0%, #fff 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        @yield('styles')
    </style>
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>
