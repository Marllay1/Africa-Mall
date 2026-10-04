<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} — Admin</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon-32.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-seller antialiased">
        <div x-data="{ collapsed: false, mobileOpen: false }" class="min-h-screen bg-admin-bg flex">

            <!-- Sidebar -->
            <aside
                :class="{ 'w-[90px]': collapsed, 'w-[270px]': !collapsed, '-translate-x-full': !mobileOpen, 'translate-x-0': mobileOpen }"
                class="fixed inset-y-0 left-0 z-40 bg-admin-sidebar text-[#f3e7d9] overflow-y-auto p-5 shadow-[2px_0_15px_rgba(0,0,0,.08)] transition-all duration-300 sm:translate-x-0">

                <div class="flex items-center gap-3 mb-7">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-[45px] h-[45px] rounded-full object-cover border-2 border-admin-border flex-shrink-0">
                    <h2 x-show="!collapsed" class="text-[#e7c7a7] text-lg font-semibold tracking-wide whitespace-nowrap">AFRICA MALL ADMIN</h2>
                </div>

                <ul class="list-none">
                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Principal') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-chart-line w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Tableau de bord') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Utilisateurs & Sellers') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-users w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Utilisateurs') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.shops.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.shops.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-store w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Boutiques') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.seller-requests.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.seller-requests.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-user-check w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Demandes vendeur') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Catalogue') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('admin.products.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-box w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Produits') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.categories.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-tags w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Catégories') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Commandes & Litiges') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-shopping-cart w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Commandes') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.disputes.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.disputes.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-gavel w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Litiges') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.reports.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.reports.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-flag w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Modération') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Finances') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('admin.payment-methods.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.payment-methods.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-credit-card w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Moyens de paiement') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.withdrawal-requests.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.withdrawal-requests.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-money-bill-wave w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Retraits') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.premium-requests.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.premium-requests.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-star w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Demandes Premium') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.customer-premium-requests.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.customer-premium-requests.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-crown w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Premium Customer') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Croissance') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('admin.advertisements.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.advertisements.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-bullhorn w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Publicités') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.coupons.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.coupons.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-percent w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Promotions') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="text-[11px] uppercase tracking-widest text-[#b9a087] font-bold px-3.5 pt-1 pb-1.5">{{ __('Système') }}</li>
                    <li class="mb-1">
                        <a href="{{ route('admin.settings.edit') }}"
                            class="flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-admin-accent text-[#fffaf2] shadow-[0_4px_12px_rgba(140,90,40,.3)] font-semibold' : 'hover:bg-admin-hover hover:text-[#fff5e6]' }}">
                            <i class="fas fa-sliders-h w-4 text-center"></i>
                            <span x-show="!collapsed">{{ __('Paramètres') }}</span>
                        </a>
                    </li>

                    <li x-show="!collapsed" class="h-px bg-white/[.08] my-3"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('{{ __('Voulez-vous vraiment vous déconnecter ?') }}')">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 py-2.5 px-3.5 rounded-xl text-sm font-medium hover:bg-admin-hover hover:text-[#fff5e6] transition text-left">
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
                        <h1 class="text-seller-sidebar font-semibold text-lg hidden sm:block">AfricaMall Admin</h1>
                    </div>

                    <div class="flex items-center gap-4.5">
                        <div class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-[18px] shadow-[0_6px_16px_rgba(100,60,20,.06)] border border-[#ede3d3]">
                            <div class="w-[45px] h-[45px] rounded-full bg-admin-accent border-2 border-admin-border flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-sm hidden sm:block">
                                <strong class="text-seller-sidebar">{{ auth()->user()->name }}</strong><br>
                                <small class="text-[#7b5e47]">{{ __('Administrateur') }}</small>
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

        @stack('scripts')
    </body>
</html>
