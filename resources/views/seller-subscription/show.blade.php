<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Devenir vendeur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'seller-request-submitted')
                <div class="bg-green-100 border border-green-300 text-green-800 text-sm rounded-md p-4">
                    {{ __('Votre demande a bien été envoyée. Elle sera examinée par un administrateur.') }}
                </div>
            @endif

            @if (! $sellerProfile)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-6">
                        {{ __('Le compte Vendeur reste rattaché à votre compte Customer : vous continuez à acheter normalement tout en gérant votre boutique une fois votre demande validée.') }}
                    </p>

                    <form method="POST" action="{{ route('seller-subscription.store') }}" class="space-y-4" x-data="{ mode: '{{ old('payment_mode') }}' }">
                        @csrf

                        <div>
                            <x-input-label for="shop_name" :value="__('Nom de la boutique / entreprise')" />
                            <x-text-input id="shop_name" name="shop_name" class="block mt-1 w-full" :value="old('shop_name')" required />
                            <x-input-error :messages="$errors->get('shop_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="regime" :value="__('Régime de l\'entreprise')" />
                            <x-text-input id="regime" name="regime" class="block mt-1 w-full" :value="old('regime')" />
                            <x-input-error :messages="$errors->get('regime')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="adresse" :value="__('Adresse professionnelle')" />
                            <x-text-input id="adresse" name="adresse" class="block mt-1 w-full" :value="old('adresse')" required />
                            <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="payment_mode" :value="__('Mode de paiement')" />
                            <select id="payment_mode" name="payment_mode" x-model="mode" required
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('-- Sélectionnez un mode de paiement --') }}</option>
                                <option value="orange" @selected(old('payment_mode') === 'orange')>Orange Money / MTN Money</option>
                                <option value="paypal" @selected(old('payment_mode') === 'paypal')>PayPal</option>
                                <option value="banque" @selected(old('payment_mode') === 'banque')>{{ __('Compte bancaire') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_mode')" class="mt-2" />
                        </div>

                        <div x-show="mode === 'orange'" x-cloak>
                            <x-input-label for="numero_om" :value="__('Numéro de téléphone')" />
                            <x-text-input id="numero_om" name="numero_om" class="block mt-1 w-full" :value="old('numero_om')" />
                            <x-input-error :messages="$errors->get('numero_om')" class="mt-2" />
                        </div>

                        <div x-show="mode === 'paypal'" x-cloak>
                            <x-input-label for="email_paypal" :value="__('Adresse email PayPal')" />
                            <x-text-input id="email_paypal" type="email" name="email_paypal" class="block mt-1 w-full" :value="old('email_paypal')" />
                            <x-input-error :messages="$errors->get('email_paypal')" class="mt-2" />
                        </div>

                        <div x-show="mode === 'banque'" x-cloak class="space-y-4">
                            <div>
                                <x-input-label for="nom_banque" :value="__('Nom de la banque')" />
                                <x-text-input id="nom_banque" name="nom_banque" class="block mt-1 w-full" :value="old('nom_banque')" />
                            </div>
                            <div>
                                <x-input-label for="numero_compte" :value="__('Numéro de compte')" />
                                <x-text-input id="numero_compte" name="numero_compte" class="block mt-1 w-full" :value="old('numero_compte')" />
                            </div>
                            <div>
                                <x-input-label for="titulaire_compte" :value="__('Nom du titulaire')" />
                                <x-text-input id="titulaire_compte" name="titulaire_compte" class="block mt-1 w-full" :value="old('titulaire_compte')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="devise" :value="__('Devise')" />
                            <select id="devise" name="devise" required
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach (['XOF' => 'Franc CFA (XOF)', 'USD' => 'Dollar américain (USD)', 'EUR' => 'Euro (EUR)'] as $code => $label)
                                    <option value="{{ $code }}" @selected(old('devise', 'XOF') === $code)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('devise')" class="mt-2" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Envoyer ma demande') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            @elseif ($sellerProfile->status === 'pending')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-2">{{ __('Demande en attente de validation') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Votre demande pour la boutique :strong a été envoyée le :date et est en cours d\'examen par un administrateur.', ['strong' => '"'.$sellerProfile->shop_name.'"', 'date' => $sellerProfile->submitted_at->format('d/m/Y à H:i')]) }}</p>
                </div>
            @elseif ($sellerProfile->status === 'active')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-2">{{ __('Votre compte Vendeur est actif') }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ __('Boutique :name', ['name' => $sellerProfile->shop_name]) }}</p>
                    <a href="{{ route('seller.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-md">
                        {{ __('Accéder au Seller Center') }}
                    </a>
                </div>
            @elseif ($sellerProfile->status === 'rejected')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-2">{{ __('Demande refusée') }}</h3>
                    @if ($sellerProfile->rejection_reason)
                        <p class="text-sm text-gray-600">{{ $sellerProfile->rejection_reason }}</p>
                    @endif
                </div>
            @elseif ($sellerProfile->status === 'suspended')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-2">{{ __('Compte Vendeur suspendu') }}</h3>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
