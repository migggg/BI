<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                margin: 0;
                padding: 0;
                background-color: #151c2c;
                font-family: 'Figtree', sans-serif;
            }

            .login-page {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }

            .login-card {
                background: #1e2640;
                border-radius: 16px;
                padding: 2.5rem;
                width: 100%;
                max-width: 400px;
            }

            .login-logo-wrap {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 2rem;
            }

            .login-logo-wrap svg {
                width: 48px;
                height: 48px;
                fill: white;
            }

            .login-brand {
                font-size: 20px;
                font-weight: 600;
                color: #ffffff;
                letter-spacing: -0.3px;
            }

            .login-subtitle {
                font-size: 13px;
                color: #6b7a99;
            }

            .login-divider {
                height: 1px;
                background: #2a3450;
                margin-bottom: 1.75rem;
            }
        </style>
    </head>
    <body>
        <div class="login-page">
            <div class="login-card">
                <div class="login-logo-wrap">
                    <a href="/">
                        <x-application-logo />
                    </a>
                    <div class="login-brand">{{ config('app.name', 'BI Dashboard') }}</div>
                    <div class="login-subtitle">Sign in to your account</div>
                </div>

                <div class="login-divider"></div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>