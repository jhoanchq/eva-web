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
        body { background: #0a0e1a; font-family: 'Inter', sans-serif; }
        .auth-card {
            background: #0f1524;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8be9fd;
            text-align: center;
        }
        .auth-subtitle {
            color: #6272a4;
            font-size: 0.85rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .auth-input {
            background: #0a0e1a !important;
            border: 1px solid #30363d !important;
            border-radius: 8px !important;
            color: #e6edf3 !important;
            font-size: 0.85rem !important;
            padding: 0.6rem 0.8rem !important;
            width: 100% !important;
            transition: border-color 0.2s;
        }
        .auth-input:focus {
            border-color: #8be9fd !important;
            box-shadow: 0 0 0 2px rgba(139,233,253,0.15) !important;
        }
        .auth-input::placeholder { color: #484f58; }
        .auth-label { color: #8b949e; font-size: 0.8rem; font-weight: 500; margin-bottom: 0.3rem; display: block; }
        .auth-btn {
            background: #8be9fd;
            color: #0a0e1a;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            font-size: 0.85rem;
        }
        .auth-btn:hover { background: #6bd5e8; transform: translateY(-1px); }
        .auth-link { color: #8be9fd; font-size: 0.8rem; }
        .auth-link:hover { color: #6bd5e8; text-decoration: underline; }
        .auth-error { color: #ff5555; font-size: 0.75rem; margin-top: 0.3rem; }
        .auth-checkbox { accent-color: #8be9fd; }
        .logo-icon { font-size: 2.5em; color: #8be9fd; display: block; text-align: center; margin-bottom: 0.2rem; }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <div class="text-center mb-4">
            <a href="/" class="no-underline">
                <i class="fa-solid fa-cloud-arrow-up logo-icon"></i>
                <div class="auth-title">EVA-WEB</div>
                <div class="auth-subtitle">Evaluación y Control de Servicios Web</div>
            </a>
        </div>
        <div class="w-full sm:max-w-md auth-card p-6 sm:p-8">
            {{ $slot }}
        </div>
        <p class="mt-4 text-xs text-center" style="color:#484f58;">
            IESTP Jorge Basadre — 2026-I
        </p>
    </div>
</body>
</html>
