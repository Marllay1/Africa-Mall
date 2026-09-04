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
        <div class="min-h-screen bg-cream">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-20 sm:pb-0">
                {{ $slot }}
            </main>

            <!-- Mobile bottom nav -->
            <nav class="sm:hidden fixed bottom-0 inset-x-0 bg-white border-t border-beige flex justify-around items-center py-2 z-40">
                <a href="{{ route('products.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('products.*') ? 'text-choco font-semibold' : 'text-choco-soft' }}">
                    <i class="fas fa-store text-lg mb-0.5"></i>
                    {{ __('Accueil') }}
                </a>
                @auth
                    <a href="{{ route('cart.show') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('cart.*') ? 'text-choco font-semibold' : 'text-choco-soft' }}">
                        <i class="fas fa-shopping-cart text-lg mb-0.5"></i>
                        {{ __('Panier') }}
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('orders.*') ? 'text-choco font-semibold' : 'text-choco-soft' }}">
                        <i class="fas fa-box text-lg mb-0.5"></i>
                        {{ __('Commandes') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('dashboard') ? 'text-choco font-semibold' : 'text-choco-soft' }}">
                        <i class="fas fa-user text-lg mb-0.5"></i>
                        {{ __('Compte') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex flex-col items-center text-xs text-choco-soft">
                        <i class="fas fa-user text-lg mb-0.5"></i>
                        {{ __('Connexion') }}
                    </a>
                @endauth
            </nav>
        </div>
    </body>
</html>
