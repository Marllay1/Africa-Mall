<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Boutiques') }}
        </h2>
    </x-slot>

    <div class="bg-gray-800 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-700 text-sm">
            <thead>
                <tr class="text-left text-gray-400">
                    <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Propriétaire') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Produits') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Commandes') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Créée le') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700 text-gray-200">
                @forelse ($shops as $shop)
                    <tr>
                        <td class="px-6 py-4">{{ $shop->name }}</td>
                        <td class="px-6 py-4">{{ $shop->sellerProfile->user->name }}</td>
                        <td class="px-6 py-4">{{ $shop->products_count }}</td>
                        <td class="px-6 py-4">{{ $shop->orders_count }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $shop->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune boutique pour le moment.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-gray-300">{{ $shops->links() }}</div>
</x-admin-layout>
