<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Panier') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') && session('status') !== 'cart-empty')
                <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm rounded-md p-4">
                    @switch(session('status'))
                        @case('added-to-cart') {{ __('Produit ajouté au panier.') }} @break
                        @case('stock-insufficient') {{ __('Le stock disponible ne permet plus cette commande, ajustez les quantités.') }} @break
                        @case('out-of-stock') {{ __('Ce produit est en rupture de stock.') }} @break
                    @endswitch
                </div>
            @endif

            @if (empty($lines))
                <div class="flex flex-col items-center text-center py-16 px-6">
                    <div class="w-20 h-20 rounded-full bg-cream flex items-center justify-center mb-5">
                        <i class="fas fa-cart-shopping text-3xl text-choco-soft"></i>
                    </div>
                    <h3 class="text-lg font-bold text-choco-dark mb-1.5">{{ __('Votre panier est vide') }}</h3>
                    <p class="text-sm text-choco-soft mb-6 max-w-xs">{{ __('Parcourez le marché AfricaMall et ajoutez des produits pour commencer votre commande.') }}</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-choco hover:bg-choco-light text-white font-bold px-6 py-3 rounded-full transition">
                        <i class="fas fa-store"></i> {{ __('Parcourir les produits') }}
                    </a>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-2xl border border-beige divide-y divide-beige">
                    @foreach ($lines as $line)
                        <div class="flex items-center gap-4 p-4">
                            <div class="w-16 h-16 bg-cream rounded-lg flex-shrink-0 overflow-hidden">
                                @if ($line['product']->image_url)
                                    <img src="{{ $line['product']->image_url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-choco-dark truncate">{{ $line['product']->name }}</p>
                                <p class="text-xs text-choco-soft">{{ $line['product']->shop->name }}</p>
                            </div>
                            <form method="POST" action="{{ route('cart.update', $line['product']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $line['quantity'] }}" min="1" max="{{ $line['product']->stock }}"
                                    class="w-16 border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm text-sm">
                                <button class="text-xs text-choco-soft underline">{{ __('Mettre à jour') }}</button>
                            </form>
                            <p class="w-24 text-right text-sm font-semibold text-choco-dark">{{ number_format($line['line_total'], 0, ',', ' ') }} {{ $line['product']->devise }}</p>
                            <form method="POST" action="{{ route('cart.remove', $line['product']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 text-xs">{{ __('Retirer') }}</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white shadow-sm rounded-2xl border border-beige p-4 flex items-center justify-between">
                    <span class="font-semibold text-choco-dark">{{ __('Total') }}</span>
                    <span class="text-xl font-extrabold text-choco">{{ number_format($total, 0, ',', ' ') }} XOF</span>
                </div>

                <a href="{{ route('cart.payment') }}" class="block w-full text-center bg-choco hover:bg-choco-light text-white font-bold py-3.5 rounded-full">
                    {{ __('Valider la commande') }}
                </a>
            @endif

        </div>
    </div>
</x-app-layout>
