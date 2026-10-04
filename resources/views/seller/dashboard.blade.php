<x-seller-layout>
    @php
        $variationBadge = function (?float $variation) {
            if ($variation === null) {
                return '<span class="text-xs text-[#9c8569]">'.__('Aucune donnée sur la période précédente').'</span>';
            }
            $positive = $variation >= 0;
            $color = $positive ? 'text-[#4b6b2c]' : 'text-[#b34a3b]';
            $icon = $positive ? 'fa-arrow-up' : 'fa-arrow-down';

            return '<span class="text-xs font-semibold '.$color.'"><i class="fas '.$icon.'"></i> '.number_format(abs($variation), 1, ',', ' ').'%</span>';
        };
    @endphp

    <div class="flex items-center justify-end gap-2 mb-5">
        <span class="text-sm text-[#7b5e47]">{{ __('Période') }}</span>
        @foreach ($periods as $p)
            <a href="{{ route('seller.dashboard', ['period' => $p]) }}"
                class="text-xs px-3.5 py-1.5 rounded-full font-semibold {{ $period === $p ? 'text-white' : 'bg-[#f0e2d0] text-[#7b5e47]' }}"
                @if ($period === $p) style="background: linear-gradient(135deg,#c29a6a,#a7754b);" @endif>
                {{ ['7j' => __('7 jours'), '30j' => __('30 jours'), '90j' => __('90 jours')][$p] }}
            </a>
        @endforeach
    </div>

    <div class="grid gap-5" style="grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));">
        <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
            <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Revenus (période)') }}</h3>
            <h1 class="text-[32px] text-seller-sidebar mb-1.5">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</h1>
            {!! $variationBadge($revenueVariation) !!}
            <i class="fas fa-wallet absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
        </div>
        <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
            <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Produits Actifs') }}</h3>
            <h1 class="text-[32px] text-seller-sidebar mb-1.5">{{ $activeProductsCount }}</h1>
            {!! $variationBadge($newProductsVariation) !!}
            <p class="text-xs text-[#7b5e47] mt-1.5">{{ $pendingProductsCount }} {{ __('en attente') }} &middot; {{ $outOfStockProductsCount }} {{ __('en rupture') }}</p>
            <i class="fas fa-box-open absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
        </div>
        <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
            <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Commandes (période)') }}</h3>
            <h1 class="text-[32px] text-seller-sidebar mb-1.5">{{ $ordersCount }}</h1>
            {!! $variationBadge($ordersVariation) !!}
            <p class="text-xs text-[#7b5e47] mt-1.5">{{ $pendingOrdersCount }} {{ __('en attente') }} &middot; {{ $toShipOrdersCount }} {{ __('à expédier') }}</p>
            <i class="fas fa-shopping-cart absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
        </div>
        <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
            <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Clients') }}</h3>
            <h1 class="text-[32px] text-seller-sidebar mb-1.5">{{ $customersCount }}</h1>
            {!! $variationBadge($newCustomersVariation) !!}
            <p class="text-xs text-[#7b5e47] mt-1.5">{{ $newCustomersCount }} {{ $newCustomersCount > 1 ? __('nouveaux sur la période') : __('nouveau sur la période') }}</p>
            <i class="fas fa-users absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
        </div>
    </div>

    <div class="grid gap-5 mt-6" style="grid-template-columns: 2fr 1fr;">
        <div class="bg-white rounded-[24px] p-5 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
            <div class="flex justify-between items-center flex-wrap gap-4 mb-5">
                <h2 class="text-seller-sidebar font-semibold">{{ __('Produits Récents') }}</h2>
                <a href="{{ route('seller.products.create') }}" class="inline-flex items-center gap-2 text-white font-semibold px-5 py-3 rounded-2xl" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">
                    <i class="fas fa-plus-circle"></i> {{ __('Ajouter Produit') }}
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr>
                            <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Produit') }}</th>
                            <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Prix') }}</th>
                            <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Stock') }}</th>
                            <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Statut') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentProducts as $product)
                            <tr>
                                <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-[55px] h-[55px] rounded-2xl bg-cream flex-shrink-0 overflow-hidden">
                                            @if ($product->image_url)
                                                <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $product->name }}</strong><br>
                                            <small class="text-[#7b5e47]">{{ $product->category?->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->devise }}</td>
                                <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">{{ $product->stock }}</td>
                                <td class="p-3.5 border-b border-[#f0e2d0]">
                                    @if ($product->stock === 0)
                                        <span class="inline-block px-3.5 py-1.5 rounded-full text-[13px] font-semibold bg-[#fce8e6] text-[#b34a3b]">{{ __('Rupture') }}</span>
                                    @elseif (! $product->is_active)
                                        <span class="inline-block px-3.5 py-1.5 rounded-full text-[13px] font-semibold bg-[#fdf1db] text-[#b47b3c]">{{ __('Masqué') }}</span>
                                    @else
                                        <span class="inline-block px-3.5 py-1.5 rounded-full text-[13px] font-semibold bg-[#e7f0da] text-[#4b6b2c]">{{ __('Actif') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-6 text-center text-[#7b5e47]">{{ __('Aucun produit pour le moment.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-[24px] p-5 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
            <h3 class="text-seller-sidebar font-semibold mb-3">{{ __('Dernières Commandes') }}</h3>
            @forelse ($recentOrders as $order)
                <p class="text-[#3e2c1f] py-1">#AFR{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} — {{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</p>
            @empty
                <p class="text-[#7b5e47] text-sm">{{ __('Aucune commande pour le moment.') }}</p>
            @endforelse
        </div>
    </div>
</x-seller-layout>
