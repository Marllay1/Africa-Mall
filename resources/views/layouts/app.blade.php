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

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false, notifOpen: false }" class="min-h-screen bg-customer-bg pb-20">
            @include('layouts.navigation')
            @include('layouts.customer-sidebar')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Bottom nav (footer, exact 3 entries from legacy home.php) -->
            <footer class="fixed bottom-0 inset-x-0 bg-white border-t border-[#E7D9CC] flex justify-around py-2 z-[1000]">
                <a href="{{ route('products.index') }}" class="text-center text-xs {{ request()->routeIs('products.*') ? 'text-choco font-bold' : 'text-[#8B7355]' }}">
                    <i class="fas fa-home block text-xl mb-1"></i>{{ __('Accueil') }}
                </a>
                <a href="{{ auth()->check() ? route('cart.show') : route('login') }}" class="text-center text-xs {{ request()->routeIs('cart.*') ? 'text-choco font-bold' : 'text-[#8B7355]' }}">
                    <i class="fas fa-shopping-cart block text-xl mb-1"></i>{{ __('Panier') }}
                </a>
                <a href="{{ auth()->check() ? route('conversations.index') : route('login') }}" class="text-center text-xs {{ request()->routeIs('conversations.*') ? 'text-choco font-bold' : 'text-[#8B7355]' }}">
                    <i class="fas fa-comments block text-xl mb-1"></i>{{ __('Messages') }}
                </a>
            </footer>
        </div>
    </body>
</html>
