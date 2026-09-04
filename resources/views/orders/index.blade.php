<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes commandes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'order-placed')
                <div class="bg-green-100 border border-green-300 text-green-800 text-sm rounded-md p-4">
                    {{ __('Votre commande a été enregistrée.') }}
                </div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-10 text-center text-gray-500">
                    {{ __("Vous n'avez pas encore passé de commande.") }}
                </div>
            @else
                <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                    @foreach ($orders as $order)
                        <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ __('Commande') }} #{{ $order->id }} &middot; {{ $order->shop->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
                                <span class="text-xs capitalize text-gray-500">{{ $order->status }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div>{{ $orders->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
