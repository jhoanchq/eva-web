<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EVA-WEB') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #0a0e1a; font-family: 'Inter', sans-serif; color: #e6edf3; }
        .nav-bar { background: #0f1524; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .nav-link { color: #8b949e; font-weight: 500; font-size: 0.85rem; transition: color 0.2s; }
        .nav-link:hover { color: #8be9fd; }
        .nav-link-active { color: #8be9fd; border-bottom: 2px solid #8be9fd; }
        .main-card { background: #0f1524; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .header-title { color: #8be9fd; font-weight: 700; font-size: 1.1rem; }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
                <div class="header-title">{{ $header }}</div>
            </header>
        @endisset

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
