<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; {{ __('Mes commandes') }}</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">{{ __('Commande') }} #{{ $order->id }}</h1>
                        <p class="text-sm text-gray-500">{{ $order->shop->name }} &middot; {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="text-sm capitalize bg-gray-100 px-3 py-1 rounded-full text-gray-700">{{ $order->status }}</span>
                </div>

                <div class="divide-y border-t">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->quantity }} &times; {{ number_format($item->unit_price, 0, ',', ' ') }} {{ $order->devise }}</p>
                            </div>
                            <p class="text-sm font-semibold">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }} {{ $order->devise }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-4 border-t mt-4">
                    <span class="font-semibold text-gray-800">{{ __('Total') }}</span>
                    <span class="text-xl font-bold text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
