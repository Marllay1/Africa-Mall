<x-seller-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Commandes reçues') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        @if (session('status') === 'order-status-updated')
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
                {{ __('Statut de la commande mis à jour.') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-2xl border border-beige divide-y divide-beige">
            @forelse ($orders as $order)
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-sm font-medium text-choco-dark">{{ __('Commande') }} #{{ $order->id }} &middot; {{ $order->user->name }}</p>
                            <p class="text-xs text-choco-soft">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <p class="font-semibold text-choco-dark">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
                    </div>

                    <ul class="text-xs text-choco-soft mb-3">
                        @foreach ($order->items as $item)
                            <li>{{ $item->quantity }} &times; {{ $item->product->name }}</li>
                        @endforeach
                    </ul>

                    <form method="POST" action="{{ route('seller.orders.update-status', $order) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="text-xs border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button class="text-xs px-3 py-1.5 bg-choco hover:bg-choco-light text-white rounded-md">{{ __('Mettre à jour') }}</button>
                    </form>
                </div>
            @empty
                <div class="p-10 text-center text-choco-soft">{{ __("Aucune commande reçue pour le moment.") }}</div>
            @endforelse
        </div>

        <div>{{ $orders->links() }}</div>

    </div>
</x-seller-layout>
