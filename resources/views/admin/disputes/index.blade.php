<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Litiges') }}
        </h2>
    </x-slot>

    <div class="space-y-8">

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('Dossiers ouverts') }} ({{ $open->count() }})</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Commande') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Ouvert le') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($open as $dispute)
                        <tr class="bg-red-950/30">
                            <td class="px-6 py-4">#AFR{{ str_pad($dispute->order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">{{ $dispute->order->user?->name ?? $dispute->order->guest_name.' ('.__('invité').')' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $dispute->order->shop->name }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $dispute->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.disputes.show', $dispute) }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs rounded-md">
                                    {{ __('Ouvrir le dossier') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucun litige en cours.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-gray-100 font-semibold">{{ __('Décisions récentes') }}</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Commande') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Décision') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Traité par') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Le') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($resolved as $dispute)
                        <tr>
                            <td class="px-6 py-4">#AFR{{ str_pad($dispute->order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                @if ($dispute->resolution === 'refunded')
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-900 text-emerald-200">{{ __('Remboursé') }}</span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-700 text-gray-300">{{ __('Rejeté') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $dispute->resolvedBy?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $dispute->resolved_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune décision pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>
