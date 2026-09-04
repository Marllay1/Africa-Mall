<x-seller-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Seller Center') }} — {{ $shop->name }}
        </h2>
    </x-slot>

    <div class="grid sm:grid-cols-2 gap-4">
        <a href="{{ route('seller.products.index') }}" class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-beige hover:shadow-md transition">
            <i class="fas fa-box-open text-gold text-xl mb-2"></i>
            <p class="font-semibold text-choco-dark">{{ __('Produits') }}</p>
            <p class="text-sm text-choco-soft mt-1">{{ __('Ajouter, modifier ou retirer des produits de votre boutique.') }}</p>
        </a>
        <a href="{{ route('seller.orders.index') }}" class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-beige hover:shadow-md transition">
            <i class="fas fa-receipt text-gold text-xl mb-2"></i>
            <p class="font-semibold text-choco-dark">{{ __('Commandes') }}</p>
            <p class="text-sm text-choco-soft mt-1">{{ __('Voir et traiter les commandes reçues.') }}</p>
        </a>
    </div>
</x-seller-layout>
