<x-seller-layout>
    <x-slot name="header">
        <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Commandes') }}</h2>
    </x-slot>

    <div class="space-y-5">

        @if (session('status') === 'order-status-updated')
            <div class="bg-[#e7f0da] border border-[#d3e3c0] text-[#4b6b2c] text-sm rounded-2xl p-4">
                {{ __('Statut de la commande mis à jour.') }}
            </div>
        @endif

        <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] divide-y divide-[#f0e2d0]">
            @forelse ($orders as $order)
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                        <div>
                            <p class="text-sm font-medium text-seller-sidebar">{{ __('Commande') }} #AFR{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} &middot; {{ $order->user->name }}</p>
                            <p class="text-xs text-[#7b5e47]">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <p class="font-semibold text-seller-sidebar">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
                    </div>

                    <ul class="text-xs text-[#7b5e47] mb-3">
                        @foreach ($order->items as $item)
                            <li>{{ $item->quantity }} &times; {{ $item->product->name }}</li>
                        @endforeach
                    </ul>

                    <form method="POST" action="{{ route('seller.orders.update-status', $order) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="text-xs border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button class="text-xs px-4 py-2 text-white rounded-2xl font-semibold" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">{{ __('Mettre à jour') }}</button>
                    </form>
                </div>
            @empty
                <div class="p-10 text-center text-[#7b5e47]">{{ __("Aucune commande reçue pour le moment.") }}</div>
            @endforelse
        </div>

        <div>{{ $orders->links() }}</div>

    </div>
</x-seller-layout>
