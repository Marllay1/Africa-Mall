<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Modération') }}
        </h2>
    </x-slot>

    <div class="space-y-8">

        @if (session('status'))
            <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-200 text-sm rounded-md p-4">
                @switch(session('status'))
                    @case('report-updated') {{ __('Signalement traité.') }} @break
                    @case('product-hidden') {{ __('Produit masqué.') }} @break
                @endswitch
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('Signalements en attente') }} ({{ $pending->count() }})</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Produit') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Motif') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Signalé par') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Le') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($pending as $report)
                        <tr>
                            <td class="px-6 py-4">{{ $report->product->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $report->product->shop->name }}</td>
                            <td class="px-6 py-4">
                                <div class="capitalize">{{ str_replace('_', ' ', $report->reason) }}</div>
                                @if ($report->description)
                                    <div class="text-gray-400 text-xs mt-0.5">{{ $report->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $report->reporter?->name ?? __('invité') }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if ($report->product->is_active)
                                    <form method="POST" action="{{ route('admin.products.toggle-visibility', $report->product) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs rounded-md">{{ __('Masquer le produit') }}</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.reports.review', $report) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="reviewed">
                                    <button class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs rounded-md">{{ __('Marquer traité') }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reports.review', $report) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="dismissed">
                                    <button class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded-md">{{ __('Rejeter') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">{{ __('Aucun signalement en attente.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('Traités récemment') }}</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Produit') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Traité par') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Le') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($resolved as $report)
                        <tr>
                            <td class="px-6 py-4">{{ $report->product->name }}</td>
                            <td class="px-6 py-4 capitalize">{{ $report->status }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $report->reviewer?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $report->reviewed_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">{{ __('Aucun signalement traité pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>
