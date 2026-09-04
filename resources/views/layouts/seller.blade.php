<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Seller Center') }} &bull; Africa Mall</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-seller antialiased">
        <div x-data="{ collapsed: false, mobileOpen: false }" class="min-h-screen bg-seller-bg flex">

            <!-- Sidebar -->
            <aside
                :class="{ 'w-[90px]': collapsed, 'w-[270px]': !collapsed, '-translate-x-full': !mobileOpen, 'translate-x-0': mobileOpen }"
                class="fixed inset-y-0 left-0 z-40 bg-seller-sidebar text-[#f3e7d9] overflow-y-auto p-5 shadow-[2px_0_15px_rgba(0,0,0,.08)] transition-all duration-300 sm:translate-x-0">

                <div class="flex items-center gap-3 mb-7">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-[45px] h-[45px] rounded-full object-cover border-2 border-seller-border flex-shrink-0">
                    <h2 x-show="!collapsed" class="text-[#e7c7a7] text-lg font-semibold tracking-wide whitespace-nowrap">AFRICA MALL</h2>
                </div>

                <ul class="list-none">
                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Principal') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('seller.dashboard') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.dashboard') ? 'bg-seller-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-seller-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-chart-line w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Dashboard') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Boutique') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('seller.products.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.products.*') ? 'bg-seller-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-seller-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-store w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Ma Boutique') }}</span>
                        </a>
                    </li>
                    <ul x-show="!collapsed" class="pl-2">
                        <li>
                            <a href="{{ route('seller.products.index') }}"
                                class="flex items-center gap-3 py-2 px-3.5 rounded-xl text-[13px] font-medium transition {{ request()->routeIs('seller.products.*') ? 'text-[#fffaf2]' : 'text-[#f3e7d9]/80 hover:bg-seller-hover' }}">
                                <i class="fas fa-box w-3 text-center text-[12px]"></i>
                                <span>{{ __('Produits') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.orders.index') }}"
                                class="flex items-center gap-3 py-2 px-3.5 rounded-xl text-[13px] font-medium transition {{ request()->routeIs('seller.orders.*') ? 'text-[#fffaf2]' : 'text-[#f3e7d9]/80 hover:bg-seller-hover' }}">
                                <i class="fas fa-shopping-cart w-3 text-center text-[12px]"></i>
                                <span>{{ __('Commandes') }}</span>
                            </a>
                        </li>
                    </ul>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Finances') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('seller.revenues') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.revenues') ? 'bg-seller-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-seller-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-wallet w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Revenus') }}</span>
                        </a>
                    </li>
                    <ul x-show="!collapsed" class="pl-2">
                        <li>
                            <a href="{{ route('seller.statistics') }}"
                                class="flex items-center gap-3 py-2 px-3.5 rounded-xl text-[13px] font-medium transition {{ request()->routeIs('seller.statistics') ? 'text-[#fffaf2]' : 'text-[#f3e7d9]/80 hover:bg-seller-hover' }}">
                                <i class="fas fa-chart-pie w-3 text-center text-[12px]"></i>
                                <span>{{ __('Statistiques') }}</span>
                            </a>
                        </li>
                    </ul>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Communication') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('seller.conversations.index') }}" x-data="unreadBadge('{{ route('seller.conversations.badge') }}')"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.conversations.*') ? 'bg-seller-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-seller-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-comment-dots w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Messages') }}</span>
                            <span x-show="count > 0 && !collapsed" x-text="count" x-cloak class="ms-auto bg-[#c25a3a] text-white text-[10px] font-semibold rounded-full px-1.5 py-0.5"></span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="h-px bg-white/[.08] my-3"></li>

                    <li class="mb-1">
                        <a href="{{ route('seller.premium') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.premium') ? 'bg-seller-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-seller-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-star w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Premium') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('seller.settings') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('seller.settings') ? 'bg-seller-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-seller-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-cog w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Paramètres') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium hover:bg-seller-hover hover:text-[#fff5e6] transition">
                            <i class="fas fa-arrow-left w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Espace Customer') }}</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('{{ __('Voulez-vous vraiment vous déconnecter ?') }}')">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium hover:bg-seller-hover hover:text-[#fff5e6] transition text-left">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                                <span x-show="!collapsed">{{ __('Déconnexion') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </aside>

            <!-- Mobile overlay -->
            <div x-show="mobileOpen" @click="mobileOpen = false" x-cloak class="fixed inset-0 bg-black/30 z-30 sm:hidden"></div>

            <!-- Main -->
            <div :class="collapsed ? 'sm:ml-[90px]' : 'sm:ml-[270px]'" class="flex-1 min-w-0 transition-all duration-300 p-5 sm:p-7">

                <!-- Topbar -->
                <div class="flex flex-wrap items-center justify-between gap-5 mb-7">
                    <div class="flex items-center gap-3.5 flex-1 min-w-[240px]">
                        <button @click="window.innerWidth <= 800 ? (mobileOpen = !mobileOpen) : (collapsed = !collapsed)"
                            class="w-[45px] h-[45px] rounded-2xl bg-white shadow-[0_6px_16px_rgba(110,70,30,.08)] text-[#5e3e2b] flex-shrink-0">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="bg-white px-5 py-3 rounded-[18px] flex items-center gap-3 flex-1 shadow-[0_6px_18px_rgba(100,60,20,.06)] border border-[#ede3d3]">
                            <i class="fas fa-search text-[#5e3e2b]"></i>
                            <input type="text" placeholder="{{ __('Rechercher...') }}" class="border-none outline-none w-full bg-transparent p-0 focus:ring-0 text-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-4.5">
                        <a href="{{ route('seller.conversations.index') }}" x-data="unreadBadge('{{ route('seller.conversations.badge') }}')"
                            class="w-[45px] h-[45px] rounded-2xl bg-white shadow-[0_6px_16px_rgba(100,60,20,.06)] flex items-center justify-center relative text-[#5e3e2b]">
                            <i class="fas fa-comment-dots"></i>
                            <span x-show="count > 0" x-text="count" x-cloak class="absolute -top-1.5 -right-1.5 bg-[#c25a3a] text-white w-[19px] h-[19px] rounded-full text-[11px] font-bold flex items-center justify-center"></span>
                        </a>

                        <div class="w-[45px] h-[45px] rounded-2xl bg-white shadow-[0_6px_16px_rgba(100,60,20,.06)] flex items-center justify-center text-[#5e3e2b]">
                            <i class="fas fa-bell"></i>
                        </div>

                        <div class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-[18px] shadow-[0_6px_16px_rgba(100,60,20,.06)] border border-[#ede3d3]">
                            <div class="w-[45px] h-[45px] rounded-full bg-seller-accent border-2 border-seller-border flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-sm hidden sm:block">
                                <strong class="text-seller-sidebar">{{ auth()->user()->name }}</strong><br>
                                <small class="text-[#7b5e47]">{{ __('Vendeur') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                @isset($header)
                    <div class="mb-6">{{ $header }}</div>
                @endisset

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
