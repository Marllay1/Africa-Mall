<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Mes favoris') }}
        </h2>
    </x-slot>

    <div class="py-8 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($products->isEmpty())
                <div class="bg-white shadow-sm rounded-2xl border border-beige p-10 text-center text-choco-soft">
                    {{ __('Vous n\'avez pas encore de produit favori.') }}
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div>{{ $products->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
