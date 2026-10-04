<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Publicités') }}
        </h2>
    </x-slot>

    <div class="space-y-8">

        @if (session('status'))
            <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-200 text-sm rounded-md p-4">
                @switch(session('status'))
                    @case('advertisement-approved') {{ __('Campagne approuvée.') }} @break
                    @case('advertisement-rejected') {{ __('Campagne refusée.') }} @break
                @endswitch
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('En attente') }} ({{ $pending->count() }})</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Produit') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Budget') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Durée') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($pending as $ad)
                        <tr>
                            <td class="px-6 py-4">{{ $ad->shop->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $ad->product->name ?? '—' }}</td>
                            <td class="px-6 py-4">{{ number_format($ad->budget, 0, ',', ' ') }} XOF</td>
                            <td class="px-6 py-4 text-gray-400">{{ $ad->duration_days }} {{ __('jours') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form method="POST" action="{{ route('admin.advertisements.approve', $ad) }}" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs rounded-md">{{ __('Approuver') }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.advertisements.reject', $ad) }}" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs rounded-md">{{ __('Rejeter') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune demande en attente.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('Campagnes actives') }} ({{ $active->count() }})</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Produit') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Fin') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Impressions') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Clics') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($active as $ad)
                        <tr>
                            <td class="px-6 py-4">{{ $ad->shop->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $ad->product->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $ad->ends_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $ad->impressions_count }}</td>
                            <td class="px-6 py-4">{{ $ad->clicks_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune campagne active.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('Historique') }}</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Produit') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Impressions') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Clics') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($history as $ad)
                        <tr>
                            <td class="px-6 py-4">{{ $ad->shop->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $ad->product->name ?? '—' }}</td>
                            <td class="px-6 py-4 capitalize">{{ $ad->status === 'active' ? __('Terminée') : __('Refusée') }}</td>
                            <td class="px-6 py-4">{{ $ad->impressions_count }}</td>
                            <td class="px-6 py-4">{{ $ad->clicks_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucun historique pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>
