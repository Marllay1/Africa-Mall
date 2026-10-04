<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Commandes') }}
        </h2>
    </x-slot>

    <div class="space-y-4">
        @if ($litigeCount > 0)
            <div class="bg-red-900/40 border border-red-700 text-red-200 text-sm rounded-lg p-4">
                {{ __(':n commande(s) en litige nécessitent une attention.', ['n' => $litigeCount]) }}
            </div>
        @endif

        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-full {{ request('status') ? 'bg-gray-800 text-gray-300' : 'bg-white text-gray-900' }}">{{ __('Tous') }}</a>
            @foreach ($statuses as $status)
                <a href="{{ route('admin.orders.index', ['status' => $status]) }}" class="px-3 py-1.5 rounded-full capitalize {{ request('status') === $status ? 'bg-white text-gray-900' : 'bg-gray-800 text-gray-300' }}">{{ $status }}</a>
            @endforeach
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="px-6 py-3 font-medium">{{ __('Commande') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Total') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200">
                    @forelse ($orders as $order)
                        <tr class="{{ $order->status === 'litige' ? 'bg-red-950/40' : '' }}">
                            <td class="px-6 py-4">#AFR{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">{{ $order->user?->name ?? $order->guest_name.' ('.__('invité').')' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $order->shop->name }}</td>
                            <td class="px-6 py-4">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</td>
                            <td class="px-6 py-4 capitalize">
                                <span class="text-xs px-2 py-1 rounded-full {{ $order->status === 'litige' ? 'bg-red-900 text-red-200' : 'bg-gray-700 text-gray-300' }}">{{ $order->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune commande.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-gray-300">{{ $orders->links() }}</div>
    </div>
</x-admin-layout>
