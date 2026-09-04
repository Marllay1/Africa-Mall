<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} — Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-900">
            <nav class="bg-gray-950 border-b border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center gap-8">
                            <span class="text-white font-semibold tracking-wide">AfricaMall Admin</span>
                            <a href="{{ route('admin.seller-requests.index') }}" class="text-gray-300 hover:text-white text-sm {{ request()->routeIs('admin.seller-requests.*', 'admin.dashboard') ? 'text-white font-semibold' : '' }}">
                                {{ __('Demandes vendeur') }}
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="text-gray-300 hover:text-white text-sm {{ request()->routeIs('admin.users.*') ? 'text-white font-semibold' : '' }}">
                                {{ __('Utilisateurs') }}
                            </a>
                            <a href="{{ route('admin.shops.index') }}" class="text-gray-300 hover:text-white text-sm {{ request()->routeIs('admin.shops.*') ? 'text-white font-semibold' : '' }}">
                                {{ __('Boutiques') }}
                            </a>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-400 text-sm">{{ auth()->user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white text-sm">{{ __('Espace Customer') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="text-gray-400 hover:text-white text-sm">{{ __('Déconnexion') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            @isset($header)
                <header class="bg-gray-800 border-b border-gray-700">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
