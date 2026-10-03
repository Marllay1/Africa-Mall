<x-seller-layout>
    <x-slot name="header">
        <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Premium') }}</h2>
    </x-slot>

    <div class="space-y-6">

        @if (session('status') === 'premium-request-submitted')
            <div class="bg-[#e7f0da] border border-[#d3e3c0] text-[#4b6b2c] text-sm rounded-2xl p-4">
                {{ __('Votre demande a bien été envoyée. Elle sera examinée par un administrateur.') }}
            </div>
        @endif

        @if ($isPremium)
            <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-star text-[#c29a6a] text-xl"></i>
                    <h3 class="text-seller-sidebar font-semibold">{{ __('Votre boutique est Premium :tier', ['tier' => $currentTierLabel]) }}</h3>
                </div>
                <p class="text-sm text-[#7b5e47]">
                    {{ __('Vos produits sont mis en avant et prioritaires dans le marché Customer jusqu\'au :date.', ['date' => $latestSubscription->expires_at?->format('d/m/Y')]) }}
                </p>
            </div>
        @elseif ($latestSubscription?->isPending())
            <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6">
                <h3 class="text-seller-sidebar font-semibold mb-2">{{ __('Demande en attente de validation') }}</h3>
                <p class="text-sm text-[#7b5e47]">{{ __('Votre demande pour la formule :tier a été envoyée le :date et est en cours d\'examen par un administrateur.', ['tier' => $latestSubscription->tierLabel(), 'date' => $latestSubscription->requested_at->format('d/m/Y à H:i')]) }}</p>
            </div>
        @else
            @if ($latestSubscription?->status === 'rejected')
                <div class="bg-[#fce8e6] border border-[#f4cdc6] text-[#b34a3b] text-sm rounded-2xl p-4">
                    {{ __('Votre précédente demande a été refusée.') }}
                    @if ($latestSubscription->rejection_reason)
                        {{ $latestSubscription->rejection_reason }}
                    @endif
                </div>
            @endif

            <p class="text-sm text-[#7b5e47] max-w-2xl">
                {{ __('Boostez la visibilité de votre boutique : badge Premium sur votre boutique et vos produits, priorité dans le marché Customer et les résultats de recherche.') }}
            </p>

            <div class="grid gap-5" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
                @foreach ($tiers as $key => $tier)
                    <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 flex flex-col">
                        <h3 class="text-seller-sidebar font-semibold text-lg mb-1">{{ __('Premium :label', ['label' => $tier['label']]) }}</h3>
                        <p class="text-[28px] text-seller-sidebar font-bold mb-4">{{ number_format($tier['price'], 0, ',', ' ') }} FCFA <span class="text-sm font-normal text-[#7b5e47]">/ {{ __('mois') }}</span></p>
                        <ul class="text-sm text-[#7b5e47] space-y-2 mb-6 flex-1">
                            <li><i class="fas fa-check text-[#4b6b2c] mr-2"></i>{{ __('Badge Premium sur la boutique et les produits') }}</li>
                            <li><i class="fas fa-check text-[#4b6b2c] mr-2"></i>{{ __('Priorité dans le marché Customer') }}</li>
                            <li><i class="fas fa-check text-[#4b6b2c] mr-2"></i>{{ __('Priorité dans les résultats de recherche') }}</li>
                            @if ($key === 'pro')
                                <li><i class="fas fa-check text-[#4b6b2c] mr-2"></i>{{ __('Visibilité maximale (priorité sur la formule Basique)') }}</li>
                            @endif
                        </ul>
                        <form method="POST" action="{{ route('seller.premium.store') }}">
                            @csrf
                            <input type="hidden" name="tier" value="{{ $key }}">
                            <button class="w-full text-white font-semibold px-5 py-3 rounded-2xl" style="background: linear-gradient(135deg,#c29a6a,#a7754b);">
                                {{ __('Souscrire') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-seller-layout>
