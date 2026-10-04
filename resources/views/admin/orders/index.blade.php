<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Commandes') }}
        </h2>
    </x-slot>

    <div class="space-y-4">
        @if ($litigeCount > 0)
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4 flex items-center justify-between">
                <span>{{ __(':n commande(s) en litige nécessitent une attention.', ['n' => $litigeCount]) }}</span>
                <a href="{{ route('admin.disputes.index') }}" class="underline hover:text-red-900">{{ __('Voir les dossiers') }}</a>
            </div>
        @endif

        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-full {{ request('status') ? 'bg-[#f3e7d9] text-[#7b5e47]' : 'bg-white text-seller-sidebar' }}">{{ __('Tous') }}</a>
            @foreach ($statuses as $status)
                <a href="{{ route('admin.orders.index', ['status' => $status]) }}" class="px-3 py-1.5 rounded-full capitalize {{ request('status') === $status ? 'bg-white text-seller-sidebar' : 'bg-[#f3e7d9] text-[#7b5e47]' }}">{{ $status }}</a>
            @endforeach
        </div>

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
            <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
                <thead>
                    <tr class="text-left text-[#7b5e47]">
                        <th class="px-6 py-3 font-medium">{{ __('Commande') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Total') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Date') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                    @forelse ($orders as $order)
                        <tr class="{{ $order->status === 'litige' ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4">#AFR{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">{{ $order->user?->name ?? $order->guest_name.' ('.__('invité').')' }}</td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $order->shop->name }}</td>
                            <td class="px-6 py-4">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</td>
                            <td class="px-6 py-4 capitalize">
                                <span class="text-xs px-2 py-1 rounded-full {{ $order->status === 'litige' ? 'bg-red-100 text-red-700' : 'bg-[#f3e7d9] text-seller-sidebar' }}">{{ $order->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                @if ($order->status === 'litige')
                                    <a href="{{ route('admin.orders.dispute', $order) }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs rounded-md">{{ __('Ouvrir le dossier') }}</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-[#a8815a]">{{ __('Aucune commande.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-seller-sidebar">{{ $orders->links() }}</div>
    </div>
</x-admin-layout>
