<x-seller-layout>
    <x-slot name="header">
        <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Publicités') }}</h2>
    </x-slot>

    <div class="space-y-6">

        @if (session('status') === 'advertisement-submitted')
            <div class="bg-[#e7f0da] border border-[#d3e3c0] text-[#4b6b2c] text-sm rounded-2xl p-4">
                {{ __('Votre demande de campagne a bien été envoyée. Elle sera examinée par un administrateur.') }}
            </div>
        @endif

        @if (! $isPremium)
            <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6">
                <h3 class="text-seller-sidebar font-semibold mb-2">{{ __('Fonctionnalité Premium') }}</h3>
                <p class="text-sm text-[#7b5e47] mb-4">{{ __('Les campagnes publicitaires sont réservées aux boutiques Premium.') }}</p>
                <a href="{{ route('seller.premium') }}" class="inline-block text-white font-semibold px-5 py-2.5 rounded-2xl" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">
                    {{ __('Découvrir Premium') }}
                </a>
            </div>
        @else
            <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6">
                <h3 class="text-seller-sidebar font-semibold mb-3">{{ __('Nouvelle campagne') }}</h3>
                <form method="POST" action="{{ route('seller.advertisements.store') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    @csrf
                    <div class="sm:col-span-2">
                        <x-input-label :value="__('Produit')" />
                        <select name="product_id" required class="mt-1 block w-full border-[#f0e2d0] focus:border-[#c29a6a] focus:ring-[#c29a6a] rounded-md shadow-sm text-sm">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label :value="__('Budget (XOF)')" />
                        <x-text-input name="budget" type="number" min="1000" value="5000" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label :value="__('Durée (jours)')" />
                        <x-text-input name="duration_days" type="number" min="1" max="30" value="7" class="mt-1 block w-full" required />
                    </div>
                    <div class="sm:col-span-4">
                        <x-primary-button type="submit">{{ __('Soumettre la campagne') }}</x-primary-button>
                    </div>
                </form>
            </div>
        @endif

        <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] overflow-hidden">
            <table class="min-w-full divide-y divide-[#f0e2d0] text-sm">
                <thead>
                    <tr class="text-left text-[#7b5e47]">
                        <th class="px-6 py-3 font-medium">{{ __('Produit') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Budget') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Durée') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Statut') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Impressions') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Clics') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e2d0] text-seller-sidebar">
                    @forelse ($advertisements as $ad)
                        <tr>
                            <td class="px-6 py-4">{{ $ad->product->name ?? __('Produit supprimé') }}</td>
                            <td class="px-6 py-4">{{ number_format($ad->budget, 0, ',', ' ') }} XOF</td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $ad->duration_days }} {{ __('jours') }}</td>
                            <td class="px-6 py-4">
                                @if ($ad->isCurrentlyActive())
                                    <span class="text-xs px-2 py-1 rounded-full bg-[#e7f0da] text-[#4b6b2c]">{{ __('Active') }}</span>
                                @elseif ($ad->status === 'pending')
                                    <span class="text-xs px-2 py-1 rounded-full bg-[#fdf1da] text-[#8a6a1f]">{{ __('En attente') }}</span>
                                @elseif ($ad->status === 'rejected')
                                    <span class="text-xs px-2 py-1 rounded-full bg-[#fce8e6] text-[#b34a3b]">{{ __('Refusée') }}</span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500">{{ __('Terminée') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $ad->impressions_count }}</td>
                            <td class="px-6 py-4 text-[#7b5e47]">{{ $ad->clicks_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#7b5e47]">{{ __('Aucune campagne pour le moment.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-seller-layout>
