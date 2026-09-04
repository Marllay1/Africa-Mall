<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-beige">
                <div class="p-6 text-choco-dark">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-beige p-6">
                <h3 class="font-semibold text-choco-dark mb-2">{{ __('Statut de votre compte') }}</h3>
                <p class="text-sm text-choco-soft">
                    @php $profile = auth()->user()->sellerProfile @endphp
                    @if (! $profile)
                        {{ __('Customer.') }}
                        <a href="{{ route('seller-subscription.show') }}" class="text-choco underline">{{ __('Devenir vendeur') }}</a>
                    @elseif ($profile->status === 'pending')
                        {{ __('Customer — demande Vendeur en attente de validation.') }}
                    @elseif ($profile->status === 'active')
                        {{ __('Customer + Seller actif.') }}
                        <a href="{{ route('seller.dashboard') }}" class="text-choco underline">{{ __('Seller Center') }}</a>
                    @else
                        {{ __('Customer — demande Vendeur :status', ['status' => $profile->status]) }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
