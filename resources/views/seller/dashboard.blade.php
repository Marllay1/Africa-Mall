<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seller Center') }} — {{ $shop->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-600 text-sm">
                {{ __('Votre boutique est active. La gestion des produits, commandes et statistiques sera ajoutée dans une prochaine phase.') }}
            </div>
        </div>
    </div>
</x-app-layout>
