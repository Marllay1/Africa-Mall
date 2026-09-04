<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AfricaMall') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-choco-dark antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cream">
            <div>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 text-choco font-extrabold text-2xl tracking-tight">
                    <x-application-logo class="w-12 h-12" />
                    AfricaMall
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-sm overflow-hidden sm:rounded-2xl border border-beige">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
