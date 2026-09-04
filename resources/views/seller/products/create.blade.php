<x-seller-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Nouveau produit') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('seller.products.store') }}" class="bg-white shadow-sm rounded-2xl border border-beige p-6 space-y-4">
            @csrf
            @include('seller.products._form', ['product' => null])

            <div class="flex justify-end gap-3">
                <a href="{{ route('seller.products.index') }}" class="px-4 py-2 text-sm text-choco-soft">{{ __('Annuler') }}</a>
                <x-primary-button>{{ __('Créer le produit') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-seller-layout>
