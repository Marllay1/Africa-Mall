<nav x-data="{ open: false }" class="bg-white border-b border-beige sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-9" />
                        <span class="hidden sm:block font-extrabold text-choco tracking-tight">AfricaMall</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Produits') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('cart.show')" :active="request()->routeIs('cart.*')">
                            {{ __('Panier') }}
                        </x-nav-link>

                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                            {{ __('Mes commandes') }}
                        </x-nav-link>

                        <x-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">
                            <span x-data="unreadBadge('{{ route('conversations.badge') }}')" class="flex items-center gap-1.5">
                                {{ __('Messages') }}
                                <span x-show="count > 0" x-text="count" x-cloak class="bg-choco text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center"></span>
                            </span>
                        </x-nav-link>

                        @if (Auth::user()->isSellerActive())
                            <x-nav-link :href="route('seller.dashboard')" :active="request()->routeIs('seller.*')">
                                {{ __('Seller Center') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('seller-subscription.show')" :active="request()->routeIs('seller-subscription.*')">
                                {{ __('Devenir vendeur') }}
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                {{ __('Administration') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            @auth
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-choco-dark bg-white hover:text-choco focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                    <a href="{{ route('login') }}" class="text-sm text-choco-soft hover:text-choco">{{ __('Connexion') }}</a>
                    <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-choco text-white rounded-md hover:bg-choco-light">{{ __('Créer un compte') }}</a>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-choco-soft hover:text-choco hover:bg-cream focus:outline-none focus:bg-cream transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                {{ __('Produits') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('cart.show')" :active="request()->routeIs('cart.*')">
                    {{ __('Panier') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    {{ __('Mes commandes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">
                    {{ __('Messages') }}
                </x-responsive-nav-link>

                @if (Auth::user()->isSellerActive())
                    <x-responsive-nav-link :href="route('seller.dashboard')" :active="request()->routeIs('seller.*')">
                        {{ __('Seller Center') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('seller-subscription.show')" :active="request()->routeIs('seller-subscription.*')">
                        {{ __('Devenir vendeur') }}
                    </x-responsive-nav-link>
                @endif

                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        {{ __('Administration') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-beige">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-choco-dark">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-choco-soft">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">{{ __('Connexion') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">{{ __('Créer un compte') }}</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
