<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Litiges') }}
        </h2>
    </x-slot>

    <div class="space-y-8">

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#ede3d3]">
                <h3 class="text-seller-sidebar font-semibold">{{ __('Dossiers ouverts') }} ({{ $open->count() }})</h3>
            </div>
            <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
                <thead>
                    <tr class="text-left text-[#7b5e47]">
                        <th class="px-6 py-3 font-medium">{{ __('Commande') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Ouvert le') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                    @forelse ($open as $dispute)
                        <tr class="bg-red-50">
                            <td class="px-6 py-4">#AFR{{ str_pad($dispute->order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">{{ $dispute->order->user?->name ?? $dispute->order->guest_name.' ('.__('invité').')' }}</td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $dispute->order->shop->name }}</td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $dispute->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.disputes.show', $dispute) }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs rounded-md">
                                    {{ __('Ouvrir le dossier') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-[#a8815a]">{{ __('Aucun litige en cours.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#ede3d3]">
                <h3 class="text-seller-sidebar font-semibold">{{ __('Décisions récentes') }}</h3>
            </div>
            <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
                <thead>
                    <tr class="text-left text-[#7b5e47]">
                        <th class="px-6 py-3 font-medium">{{ __('Commande') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Décision') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Traité par') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Le') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                    @forelse ($resolved as $dispute)
                        <tr>
                            <td class="px-6 py-4">#AFR{{ str_pad($dispute->order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                @if ($dispute->resolution === 'refunded')
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">{{ __('Remboursé') }}</span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-[#f3e7d9] text-seller-sidebar">{{ __('Rejeté') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $dispute->resolvedBy?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $dispute->resolved_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-[#a8815a]">{{ __('Aucune décision pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-admin-layout>
