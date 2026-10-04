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

        <form method="GET" class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-5 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <x-input-label for="filter_search" :value="__('Rechercher')" />
                <x-text-input id="filter_search" name="search" class="block mt-1 w-full" :value="request('search')" placeholder="{{ __('Client ou numéro de commande...') }}" />
            </div>
            <div class="min-w-[160px]">
                <x-input-label for="filter_status" :value="__('Statut')" />
                <select id="filter_status" name="status" class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
                    <option value="">{{ __('Tous') }}</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <button class="px-5 py-2.5 rounded-2xl text-white font-semibold" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">{{ __('Filtrer') }}</button>
            @if (request('search') || request('status'))
                <a href="{{ route('seller.orders.index') }}" class="px-5 py-2.5 rounded-2xl bg-[#efe0d1] text-[#7b5e47] font-semibold">{{ __('Réinitialiser') }}</a>
            @endif
        </form>

        <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] divide-y divide-[#f0e2d0]">
            @forelse ($orders as $order)
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                        <div>
                            <p class="text-sm font-medium text-seller-sidebar">
                                {{ __('Commande') }} #AFR{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} &middot; {{ $order->user?->name ?? $order->guest_name.' ('.__('invité').')' }}
                                @if ($order->user?->isPremiumCustomer())
                                    <span class="ml-1 text-[0.65rem] px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-700"><i class="fas fa-star"></i> Premium</span>
                                @endif
                            </p>
                            <p class="text-xs text-[#7b5e47]">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <p class="font-semibold text-seller-sidebar">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
                    </div>

                    <ul class="text-xs text-[#7b5e47] mb-3">
                        @foreach ($order->items as $item)
                            <li>{{ $item->quantity }} &times; {{ $item->product->name }}</li>
                        @endforeach
                    </ul>

                    @if ($order->delivery_address || $order->guest_address)
                        <p class="text-xs text-[#7b5e47] mb-3">
                            <i class="fas fa-location-dot mr-1"></i>{{ $order->delivery_address ?? $order->guest_address }}
                        </p>
                    @endif

                    @php $allowed = $transitions[$order->status] ?? []; @endphp
                    @if ($allowed !== [])
                        <form method="POST" action="{{ route('seller.orders.update-status', $order) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="text-xs border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
                                <option value="{{ $order->status }}" disabled selected>{{ $order->status }}</option>
                                @foreach ($allowed as $next)
                                    <option value="{{ $next }}">{{ $next }}</option>
                                @endforeach
                            </select>
                            <button class="text-xs px-4 py-2 text-white rounded-2xl font-semibold" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">{{ __('Mettre à jour') }}</button>
                        </form>
                    @else
                        <span class="text-xs px-3 py-1.5 rounded-full bg-[#f0e2d0] text-[#7b5e47] font-medium">
                            {{ $order->status === 'litige' ? __('En litige — géré par l\'Admin') : $order->status }}
                        </span>
                    @endif
                </div>
            @empty
                <div class="p-10 text-center text-[#7b5e47]">
                    {{ request('search') || request('status') ? __('Aucune commande ne correspond à ces critères.') : __('Aucune commande reçue pour le moment.') }}
                </div>
            @endforelse
        </div>

        <div>{{ $orders->links() }}</div>

    </div>
</x-seller-layout>
