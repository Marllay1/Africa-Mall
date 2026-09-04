<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-amber-100 border border-amber-300 text-amber-800 text-sm rounded-md p-4">
                    @switch(session('status'))
                        @case('added-to-cart') {{ __('Produit ajouté au panier.') }} @break
                        @case('cart-empty') {{ __('Votre panier est vide.') }} @break
                        @case('stock-insufficient') {{ __('Le stock disponible ne permet plus cette commande, ajustez les quantités.') }} @break
                        @case('out-of-stock') {{ __('Ce produit est en rupture de stock.') }} @break
                    @endswitch
                </div>
            @endif

            @if (empty($lines))
                <div class="bg-white shadow-sm sm:rounded-lg p-10 text-center text-gray-500">
                    {{ __('Votre panier est vide.') }}
                    <a href="{{ route('products.index') }}" class="text-indigo-600 underline block mt-2">{{ __('Parcourir les produits') }}</a>
                </div>
            @else
                <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                    @foreach ($lines as $line)
                        <div class="flex items-center gap-4 p-4">
                            <div class="w-16 h-16 bg-gray-100 rounded flex-shrink-0">
                                @if ($line['product']->image_url)
                                    <img src="{{ $line['product']->image_url }}" class="w-full h-full object-cover rounded">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $line['product']->name }}</p>
                                <p class="text-xs text-gray-500">{{ $line['product']->shop->name }}</p>
                            </div>
                            <form method="POST" action="{{ route('cart.update', $line['product']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $line['quantity'] }}" min="1" max="{{ $line['product']->stock }}"
                                    class="w-16 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                <button class="text-xs text-gray-500 underline">{{ __('Mettre à jour') }}</button>
                            </form>
                            <p class="w-24 text-right text-sm font-semibold">{{ number_format($line['line_total'], 0, ',', ' ') }} {{ $line['product']->devise }}</p>
                            <form method="POST" action="{{ route('cart.remove', $line['product']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 text-xs">{{ __('Retirer') }}</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4 flex items-center justify-between">
                    <span class="font-semibold text-gray-800">{{ __('Total') }}</span>
                    <span class="text-xl font-bold text-gray-900">{{ number_format($total, 0, ',', ' ') }} XOF</span>
                </div>

                <form method="POST" action="{{ route('cart.checkout') }}">
                    @csrf
                    <x-primary-button>{{ __('Commander') }}</x-primary-button>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
