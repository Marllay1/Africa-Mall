<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('orders.index') }}" class="text-sm text-choco-soft hover:text-choco">&larr; {{ __('Mes commandes') }}</a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm rounded-2xl border border-beige p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-lg font-semibold text-choco-dark">{{ __('Commande') }} #{{ $order->id }}</h1>
                        <p class="text-sm text-choco-soft">{{ $order->shop->name }} &middot; {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="text-sm capitalize bg-cream px-3 py-1 rounded-full text-choco-dark">{{ $order->status }}</span>
                </div>

                <div class="divide-y divide-beige border-t border-beige">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-choco-dark">{{ $item->product->name }}</p>
                                <p class="text-xs text-choco-soft">{{ $item->quantity }} &times; {{ number_format($item->unit_price, 0, ',', ' ') }} {{ $order->devise }}</p>
                            </div>
                            <p class="text-sm font-semibold text-choco-dark">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }} {{ $order->devise }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-beige mt-4">
                    <span class="font-semibold text-choco-dark">{{ __('Total') }}</span>
                    <span class="text-xl font-extrabold text-choco">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
