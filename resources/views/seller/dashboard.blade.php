<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seller Center') }} — {{ $shop->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid sm:grid-cols-2 gap-4">
            <a href="{{ route('seller.products.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                <p class="font-semibold text-gray-800">{{ __('Produits') }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ __('Ajouter, modifier ou retirer des produits de votre boutique.') }}</p>
            </a>
            <a href="{{ route('seller.orders.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                <p class="font-semibold text-gray-800">{{ __('Commandes') }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ __('Voir et traiter les commandes reçues.') }}</p>
            </a>
        </div>
    </div>
</x-app-layout>
