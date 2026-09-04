<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AfricaMall') }} — Seller Center</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ collapsed: false, mobileOpen: false }" class="min-h-screen bg-cream">

            <!-- Sidebar -->
            <aside
                :class="{ 'w-20': collapsed, 'w-64': !collapsed, '-translate-x-full': !mobileOpen, 'translate-x-0': mobileOpen }"
                class="fixed inset-y-0 left-0 z-40 bg-choco-dark text-cream flex flex-col transition-all duration-200 sm:translate-x-0">

                <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
                    <img src="{{ asset('images/logo.png') }}" alt="AfricaMall" class="w-9 h-9 rounded-full flex-shrink-0">
                    <span x-show="!collapsed" class="font-bold tracking-wide text-beige">Seller Center</span>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-1">
                    <a href="{{ route('seller.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.dashboard') ? 'bg-choco text-white' : 'text-beige hover:bg-white/10' }}">
                        <i class="fas fa-gauge w-5 text-center"></i>
                        <span x-show="!collapsed">{{ __('Dashboard') }}</span>
                    </a>
                    <a href="{{ route('seller.products.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.products.*') ? 'bg-choco text-white' : 'text-beige hover:bg-white/10' }}">
                        <i class="fas fa-box-open w-5 text-center"></i>
                        <span x-show="!collapsed">{{ __('Produits') }}</span>
                    </a>
                    <a href="{{ route('seller.orders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.orders.*') ? 'bg-choco text-white' : 'text-beige hover:bg-white/10' }}">
                        <i class="fas fa-receipt w-5 text-center"></i>
                        <span x-show="!collapsed">{{ __('Commandes') }}</span>
                    </a>
                </nav>

                <div class="px-3 py-4 border-t border-white/10 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-beige hover:bg-white/10 transition">
                        <i class="fas fa-arrow-left w-5 text-center"></i>
                        <span x-show="!collapsed">{{ __('Espace Customer') }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-beige hover:bg-white/10 transition">
                            <i class="fas fa-right-from-bracket w-5 text-center"></i>
                            <span x-show="!collapsed">{{ __('Déconnexion') }}</span>
                        </button>
                    </form>
                    <button @click="collapsed = !collapsed" class="hidden sm:flex w-full items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-beige hover:bg-white/10 transition">
                        <i class="fas w-5 text-center" :class="collapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
                        <span x-show="!collapsed">{{ __('Réduire') }}</span>
                    </button>
                </div>
            </aside>

            <!-- Mobile overlay -->
            <div x-show="mobileOpen" @click="mobileOpen = false" x-cloak class="fixed inset-0 bg-black/30 z-30 sm:hidden"></div>

            <!-- Content -->
            <div :class="collapsed ? 'sm:ml-20' : 'sm:ml-64'" class="transition-all duration-200">
                <div class="sm:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-beige sticky top-0 z-20">
                    <button @click="mobileOpen = true" class="text-choco"><i class="fas fa-bars text-lg"></i></button>
                    <span class="font-semibold text-choco">Seller Center</span>
                    <span></span>
                </div>

                @isset($header)
                    <header class="bg-white border-b border-beige">
                        <div class="px-4 sm:px-8 py-6">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="px-4 sm:px-8 py-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
