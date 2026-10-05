<div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[2400]" x-show="sidebarOpen" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"></div>

<div class="fixed top-0 right-0 h-full w-[350px] max-w-[90vw] bg-white z-[2500] overflow-y-auto px-5 py-6 border-l border-beige shadow-2xl transition-transform duration-300 ease-in-out translate-x-full"
    :class="{ 'translate-x-0': sidebarOpen, 'translate-x-full': ! sidebarOpen }"
    @keydown.escape.window="sidebarOpen = false">

    <div class="flex items-center justify-between mb-7">
        <h2 class="text-choco font-bold text-lg">{{ __('Paramètres') }}</h2>
        <button @click="sidebarOpen = false" class="text-2xl text-choco-soft">&times;</button>
    </div>

    <div class="flex items-center gap-3.5 bg-[#F5EDE3] px-4 py-3.5 rounded-[20px] mb-7">
        <div class="w-[52px] h-[52px] rounded-full bg-choco-soft flex items-center justify-center text-white font-bold text-xl overflow-hidden flex-shrink-0">
            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
        </div>
        <div>
            <strong class="text-choco-dark">{{ auth()->check() ? auth()->user()->name : __('Bienvenue') }}</strong><br>
            <small class="text-choco-soft">{{ auth()->check() ? auth()->user()->email : __('Utilisateur Africa Mall') }}</small>
        </div>
    </div>

    @auth
        <div class="mb-5">
            <div class="text-[0.7rem] uppercase tracking-wide text-[#A28B72] font-bold mb-3">{{ __('Compte') }}</div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-user text-choco w-[22px]"></i> {{ __('Profil') }}</a>
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-box text-choco w-[22px]"></i> {{ __('Mes commandes') }}</a>
            <a href="{{ route('addresses.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-location-dot text-choco w-[22px]"></i> {{ __('Mes adresses') }}</a>
            <a href="{{ route('favorites.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-heart text-choco w-[22px]"></i> {{ __('Favoris') }}</a>
            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-bell text-choco w-[22px]"></i> {{ __('Notifications') }}</a>
            <a href="{{ route('premium.show') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-star text-gold w-[22px]"></i> {{ __('AfricaMall Premium') }}</a>
        </div>

        <div class="mb-5">
            <div class="text-[0.7rem] uppercase tracking-wide text-[#A28B72] font-bold mb-3">{{ __('Business') }}</div>
            @if (Auth::user()->isSellerActive())
                <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-store text-choco w-[22px]"></i> {{ __('Accéder à Seller Center') }}</a>
            @else
                <a href="{{ route('seller-subscription.show') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-store text-choco w-[22px]"></i> {{ __('Devenir vendeur / Seller Center') }}</a>
            @endif
        </div>

        <div class="mb-5">
            <div class="text-[0.7rem] uppercase tracking-wide text-[#A28B72] font-bold mb-3">{{ __('Support') }}</div>
            @if ($supportEmail)
                <a href="mailto:{{ $supportEmail }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-headset text-choco w-[22px]"></i> {{ __('Support client') }}</a>
            @endif
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-exclamation-circle text-choco w-[22px]"></i> {{ __('Litiges & remboursements') }}</a>
        </div>

        <div class="mb-5">
            <div class="text-[0.7rem] uppercase tracking-wide text-[#A28B72] font-bold mb-3">{{ __('Sécurité') }}</div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-lock text-choco w-[22px]"></i> {{ __('Sécurité') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#B85C1A] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-sign-out-alt w-[22px]"></i> {{ __('Déconnexion') }}</button>
            </form>
        </div>
    @else
        <div class="mb-5">
            <a href="{{ route('login') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-right-to-bracket text-choco w-[22px]"></i> {{ __('Connexion') }}</a>
            <a href="{{ route('register') }}" class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-[#3E2E20] font-medium hover:bg-[#F5EDE3] transition"><i class="fas fa-user-plus text-choco w-[22px]"></i> {{ __('Créer un compte') }}</a>
        </div>
    @endauth
</div>
