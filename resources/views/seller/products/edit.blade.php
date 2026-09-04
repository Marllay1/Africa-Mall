<x-seller-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Modifier le produit') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('seller.products.update', $product) }}" class="bg-white shadow-sm rounded-2xl border border-beige p-6 space-y-4">
            @csrf
            @method('PUT')
            @include('seller.products._form')

            <div class="flex justify-end gap-3">
                <a href="{{ route('seller.products.index') }}" class="px-4 py-2 text-sm text-choco-soft">{{ __('Annuler') }}</a>
                <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-seller-layout>
