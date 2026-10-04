<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('AfricaMall Premium') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'premium-request-submitted')
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
                    {{ __('Votre demande a bien été envoyée. Elle sera examinée par un administrateur.') }}
                </div>
            @endif

            @if ($isPremium)
                <div class="bg-white shadow-sm rounded-2xl border border-beige p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-star text-gold text-xl"></i>
                        <h3 class="text-choco-dark font-semibold">{{ __('Vous êtes membre AfricaMall Premium') }}</h3>
                    </div>
                    <p class="text-sm text-choco-soft">
                        {{ __('Vos commandes bénéficient automatiquement de :n% de réduction, jusqu\'au :date.', ['n' => \App\Models\CustomerPremiumSubscription::DISCOUNT_PERCENT, 'date' => $latestSubscription->expires_at?->format('d/m/Y')]) }}
                    </p>
                </div>
            @elseif ($latestSubscription?->isPending())
                <div class="bg-white shadow-sm rounded-2xl border border-beige p-6">
                    <h3 class="text-choco-dark font-semibold mb-2">{{ __('Demande en attente de validation') }}</h3>
                    <p class="text-sm text-choco-soft">{{ __('Votre demande a été envoyée le :date et est en cours d\'examen par un administrateur.', ['date' => $latestSubscription->requested_at->format('d/m/Y à H:i')]) }}</p>
                </div>
            @else
                @if ($latestSubscription?->status === 'rejected')
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-md p-4">
                        {{ __('Votre précédente demande a été refusée.') }}
                        @if ($latestSubscription->rejection_reason)
                            {{ $latestSubscription->rejection_reason }}
                        @endif
                    </div>
                @endif

                <div class="bg-white shadow-sm rounded-2xl border border-beige p-6">
                    <h3 class="text-choco-dark font-semibold text-lg mb-1">{{ __('AfricaMall Premium') }}</h3>
                    <p class="text-[28px] text-choco font-bold mb-4">{{ number_format(\App\Models\CustomerPremiumSubscription::PRICE, 0, ',', ' ') }} FCFA <span class="text-sm font-normal text-choco-soft">/ {{ __('mois') }}</span></p>
                    <ul class="text-sm text-choco-soft space-y-2 mb-6">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>{{ __(':n% de réduction automatique sur toutes vos commandes', ['n' => \App\Models\CustomerPremiumSubscription::DISCOUNT_PERCENT]) }}</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>{{ __('Support prioritaire') }}</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>{{ __('Badge Premium visible par les vendeurs') }}</li>
                    </ul>
                    <form method="POST" action="{{ route('premium.store') }}">
                        @csrf
                        <x-primary-button type="submit">{{ __('Souscrire') }}</x-primary-button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
