<x-guest-layout>
    <div class="bg-white shadow-sm rounded-2xl border border-beige p-8 text-center space-y-4">
        <i class="fas fa-circle-check text-5xl text-green-600"></i>

        <h1 class="text-xl font-bold text-choco-dark">{{ __('Commande confirmée') }}</h1>

        <p class="text-choco-soft">
            {{ __('Numéro de commande') }} :
            <strong class="text-choco-dark">#AFR{{ str_pad((string) $order->id, 4, '0', STR_PAD_LEFT) }}</strong>
        </p>

        <div class="text-left bg-cream/60 rounded-2xl p-4 space-y-1 text-sm">
            @foreach ($order->items as $item)
                <p class="text-choco-dark">{{ $item->product->name }} &times; {{ $item->quantity }}</p>
            @endforeach
            <p class="font-bold text-choco mt-2">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
        </div>

        <p class="text-sm text-choco-soft">
            {{ __('Le vendeur (:shop) va vous contacter au numéro fourni pour confirmer la livraison.', ['shop' => $order->shop->name]) }}
        </p>

        <a href="{{ route('products.index') }}" class="inline-block text-choco underline text-sm">{{ __('Retour au marché') }}</a>
    </div>
</x-guest-layout>
