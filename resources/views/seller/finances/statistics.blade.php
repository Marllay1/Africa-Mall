<x-seller-layout>
    <x-slot name="header">
        <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Statistiques') }}</h2>
    </x-slot>

    <div class="space-y-6">

        <div class="grid gap-5" style="grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));">
            <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
                <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Commandes livrées') }}</h3>
                <h1 class="text-[28px] text-seller-sidebar mb-2.5">{{ $deliveredCount }}</h1>
                <i class="fas fa-box-open absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
            </div>
            <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
                <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Commandes annulées') }}</h3>
                <h1 class="text-[28px] text-seller-sidebar mb-2.5">{{ $cancelledCount }}</h1>
                <i class="fas fa-ban absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
            </div>
            <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
                <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Panier moyen') }}</h3>
                <h1 class="text-[28px] text-seller-sidebar mb-2.5">{{ number_format($averageOrderValue, 0, ',', ' ') }} FCFA</h1>
                <i class="fas fa-chart-pie absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
            </div>
        </div>

        <div class="grid gap-5" style="grid-template-columns: 1fr 1fr;">
            <div class="bg-white rounded-[24px] p-6 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
                <h3 class="text-seller-sidebar font-semibold mb-4">{{ __('Revenus (6 derniers mois)') }}</h3>
                <canvas id="revenueChart" height="220"></canvas>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
                <h3 class="text-seller-sidebar font-semibold mb-4">{{ __('Commandes (6 derniers mois)') }}</h3>
                <canvas id="ordersChart" height="220"></canvas>
            </div>
        </div>

        @if ($isPremium)
            <div class="bg-white rounded-[24px] p-6 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-star text-[#c29a6a]"></i>
                    <h3 class="text-seller-sidebar font-semibold">{{ __('Statistiques Premium') }}</h3>
                </div>
                <p class="text-sm text-[#7b5e47] mb-5">{{ __('Vues totales de vos produits : :count', ['count' => number_format($totalViews, 0, ',', ' ')]) }}</p>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr>
                                <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Produit') }}</th>
                                <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Vues') }}</th>
                                <th class="bg-[#f9f2e7] p-3.5 text-left text-[#5e3e2b] font-medium">{{ __('Ventes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topViewedProducts as $product)
                                <tr>
                                    <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">{{ $product->name }}</td>
                                    <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">{{ number_format($product->views_count, 0, ',', ' ') }}</td>
                                    <td class="p-3.5 border-b border-[#f0e2d0] text-seller-sidebar">{{ $product->salesCount() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-6 text-center text-[#7b5e47]">{{ __('Pas encore assez de données.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-[24px] p-6 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-seller-sidebar font-semibold mb-1"><i class="fas fa-star text-[#c29a6a] mr-1"></i> {{ __('Statistiques Premium') }}</h3>
                    <p class="text-sm text-[#7b5e47]">{{ __('Passez Premium pour voir les vues et le classement de vos produits.') }}</p>
                </div>
                <a href="{{ route('seller.premium') }}" class="inline-flex items-center gap-2 text-white font-semibold px-5 py-3 rounded-2xl" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">
                    {{ __('Découvrir Premium') }}
                </a>
            </div>
        @endif

    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
        <script>
            const monthLabels = @json($monthLabels);
            const revenueByMonth = @json($revenueByMonth);
            const ordersByMonth = @json($ordersByMonth);

            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: '{{ __('Revenus') }}',
                        data: revenueByMonth,
                        borderColor: '#a7754b',
                        backgroundColor: 'rgba(167,117,75,.15)',
                        tension: 0.35,
                        fill: true,
                    }],
                },
                options: { plugins: { legend: { display: false } } },
            });

            new Chart(document.getElementById('ordersChart'), {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: '{{ __('Commandes') }}',
                        data: ordersByMonth,
                        backgroundColor: '#c29a6a',
                        borderRadius: 8,
                    }],
                },
                options: { plugins: { legend: { display: false } } },
            });
        </script>
    @endpush
</x-seller-layout>
