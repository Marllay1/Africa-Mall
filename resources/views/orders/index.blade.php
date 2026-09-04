<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Mes commandes') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'order-placed')
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
                    {{ __('Votre commande a été enregistrée.') }}
                </div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white shadow-sm rounded-2xl border border-beige p-10 text-center text-choco-soft">
                    {{ __("Vous n'avez pas encore passé de commande.") }}
                </div>
            @else
                <div class="bg-white shadow-sm rounded-2xl border border-beige divide-y divide-beige">
                    @foreach ($orders as $order)
                        <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-4 hover:bg-cream/60 transition">
                            <div>
                                <p class="text-sm font-medium text-choco-dark">{{ __('Commande') }} #{{ $order->id }} &middot; {{ $order->shop->name }}</p>
                                <p class="text-xs text-choco-soft">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-choco-dark">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
                                <span class="text-xs capitalize text-choco-soft">{{ $order->status }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div>{{ $orders->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
