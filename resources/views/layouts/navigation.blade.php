<header class="flex items-center justify-between px-5 py-3 bg-white shadow-sm sticky top-0 z-[1200]">
    <a href="{{ route('products.index') }}" class="flex items-center gap-2.5">
        <img src="{{ asset('images/logo.png') }}" alt="Africa Mall" class="w-11 h-11 rounded-full object-cover border-2 border-beige">
        <h1 class="text-2xl font-bold text-choco">AFRICA MALL</h1>
    </a>

    <div class="flex items-center gap-5">
        <button @click="notifOpen = !notifOpen" class="text-choco-soft hover:text-choco-light transition text-xl">
            <i class="fas fa-bell"></i>
        </button>
        <button @click="sidebarOpen = true" class="text-choco-soft hover:text-choco-light transition text-xl">
            <i class="fas fa-cog"></i>
        </button>
    </div>
</header>

<!-- Notifications overlay -->
<div x-show="notifOpen" x-cloak
    class="fixed inset-0 bg-black/40 z-[2800] flex items-end justify-center"
    @click.self="notifOpen = false">
    <div class="bg-white w-full max-w-lg rounded-t-[28px] p-5 max-h-[60vh] overflow-y-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-choco-dark">{{ __('Notifications') }}</h2>
            <button @click="notifOpen = false" class="text-2xl leading-none text-choco-soft">&times;</button>
        </div>
        <div class="flex flex-col items-center justify-center py-10 text-choco-soft text-center">
            <i class="fas fa-bell-slash text-3xl mb-3"></i>
            <p>{{ __('Aucune notification pour le moment.') }}</p>
        </div>
    </div>
</div>
