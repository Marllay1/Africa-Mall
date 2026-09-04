<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-2">{{ __('Statut de votre compte') }}</h3>
                <p class="text-sm text-gray-600">
                    @php $profile = auth()->user()->sellerProfile @endphp
                    @if (! $profile)
                        {{ __('Customer.') }}
                        <a href="{{ route('seller-subscription.show') }}" class="text-indigo-600 underline">{{ __('Devenir vendeur') }}</a>
                    @elseif ($profile->status === 'pending')
                        {{ __('Customer — demande Vendeur en attente de validation.') }}
                    @elseif ($profile->status === 'active')
                        {{ __('Customer + Seller actif.') }}
                        <a href="{{ route('seller.dashboard') }}" class="text-indigo-600 underline">{{ __('Seller Center') }}</a>
                    @else
                        {{ __('Customer — demande Vendeur :status', ['status' => $profile->status]) }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
