<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Boutiques') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
        <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
            <thead>
                <tr class="text-left text-[#7b5e47]">
                    <th class="px-6 py-3 font-medium">{{ __('Boutique') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Propriétaire') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Produits') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Commandes') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Créée le') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                @forelse ($shops as $shop)
                    <tr>
                        <td class="px-6 py-4">{{ $shop->name }}</td>
                        <td class="px-6 py-4">{{ $shop->sellerProfile->user->name }}</td>
                        <td class="px-6 py-4">{{ $shop->products_count }}</td>
                        <td class="px-6 py-4">{{ $shop->orders_count }}</td>
                        <td class="px-6 py-4 text-[#7b5e47]">{{ $shop->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-[#a8815a]">{{ __('Aucune boutique pour le moment.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-seller-sidebar">{{ $shops->links() }}</div>
</x-admin-layout>
