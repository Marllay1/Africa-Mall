<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-800 rounded-lg p-5">
                <p class="text-gray-400 text-sm">{{ __('Utilisateurs') }}</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $usersCount }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __(':n vendeurs actifs, :p demandes en attente', ['n' => $activeSellersCount, 'p' => $pendingSellerRequestsCount]) }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-5">
                <p class="text-gray-400 text-sm">{{ __('Produits') }}</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $productsCount }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __(':n actifs', ['n' => $activeProductsCount]) }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-5">
                <p class="text-gray-400 text-sm">{{ __('Commandes') }}</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $ordersCount }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    @foreach ($ordersByStatus as $status => $count)
                        {{ $status }}&nbsp;:&nbsp;{{ $count }}@if (! $loop->last), @endif
                    @endforeach
                </p>
            </div>
            <div class="bg-gray-800 rounded-lg p-5">
                <p class="text-gray-400 text-sm">{{ __('Revenus plateforme') }}</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($platformRevenue, 0, ',', ' ') }} XOF</p>
                <p class="text-xs text-gray-500 mt-1">{{ __(':n transactions', ['n' => $transactionsCount]) }}</p>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <h3 class="px-6 py-4 text-white font-semibold border-b border-gray-700">{{ __('Activité récente') }}</h3>
            <ul class="divide-y divide-gray-700">
                @forelse ($recentActivity as $activity)
                    <li class="px-6 py-3 text-sm text-gray-300 flex items-center justify-between gap-4">
                        <span>{{ $activity['label'] }}</span>
                        <span class="text-gray-500 text-xs flex-shrink-0">{{ $activity['date']?->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-gray-500">{{ __('Aucune activité récente.') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-admin-layout>
