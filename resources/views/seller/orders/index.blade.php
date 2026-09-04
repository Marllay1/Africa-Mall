<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Commandes reçues') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'order-status-updated')
                <div class="bg-green-100 border border-green-300 text-green-800 text-sm rounded-md p-4">
                    {{ __('Statut de la commande mis à jour.') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                @forelse ($orders as $order)
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ __('Commande') }} #{{ $order->id }} &middot; {{ $order->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
                        </div>

                        <ul class="text-xs text-gray-500 mb-3">
                            @foreach ($order->items as $item)
                                <li>{{ $item->quantity }} &times; {{ $item->product->name }}</li>
                            @endforeach
                        </ul>

                        <form method="POST" action="{{ route('seller.orders.update-status', $order) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="text-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button class="text-xs px-3 py-1.5 bg-gray-800 text-white rounded-md">{{ __('Mettre à jour') }}</button>
                        </form>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-500">{{ __("Aucune commande reçue pour le moment.") }}</div>
                @endforelse
            </div>

            <div>{{ $orders->links() }}</div>

        </div>
    </div>
</x-app-layout>
