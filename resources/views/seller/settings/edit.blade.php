<x-seller-layout>
    <x-slot name="header">
        <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Paramètres') }}</h2>
    </x-slot>

    <div class="max-w-2xl space-y-6">

        @if (session('status') === 'settings-updated')
            <div class="bg-[#e7f0da] border border-[#d3e3c0] text-[#4b6b2c] text-sm rounded-2xl p-4">
                {{ __('Vos paramètres ont été mis à jour.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('seller.settings.update') }}" class="space-y-6" x-data="{ mode: '{{ old('payment_mode', $sellerProfile->payment_mode) }}' }">
            @csrf
            @method('PATCH')

            <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-4">
                <h3 class="text-seller-sidebar font-semibold">{{ __('Profil boutique') }}</h3>

                <div>
                    <x-input-label for="name" :value="__('Nom de la boutique')" />
                    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $shop->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4"
                        class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">{{ old('description', $shop->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="logo_path" :value="__('URL du logo')" />
                    <x-text-input id="logo_path" name="logo_path" type="url" class="block mt-1 w-full" :value="old('logo_path', $shop->logo_path)" placeholder="https://..." />
                    <x-input-error :messages="$errors->get('logo_path')" class="mt-2" />
                </div>
            </div>

            <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-4">
                <h3 class="text-seller-sidebar font-semibold">{{ __('Informations commerciales') }}</h3>

                <div>
                    <x-input-label for="regime" :value="__('Régime de l\'entreprise')" />
                    <x-text-input id="regime" name="regime" class="block mt-1 w-full" :value="old('regime', $sellerProfile->regime)" />
                    <x-input-error :messages="$errors->get('regime')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="adresse" :value="__('Adresse professionnelle')" />
                    <x-text-input id="adresse" name="adresse" class="block mt-1 w-full" :value="old('adresse', $sellerProfile->adresse)" required />
                    <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="devise" :value="__('Devise')" />
                    <select id="devise" name="devise" class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
                        @foreach (['XOF' => 'Franc CFA (XOF)', 'USD' => 'Dollar américain (USD)', 'EUR' => 'Euro (EUR)'] as $code => $label)
                            <option value="{{ $code }}" @selected(old('devise', $sellerProfile->devise) === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('devise')" class="mt-2" />
                </div>
            </div>

            <div class="bg-white rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-4">
                <h3 class="text-seller-sidebar font-semibold">{{ __('Moyens de paiement / retrait') }}</h3>

                <div>
                    <x-input-label for="payment_mode" :value="__('Mode de paiement')" />
                    <select id="payment_mode" name="payment_mode" x-model="mode" required
                        class="block mt-1 w-full border-[#e0cfb5] focus:border-seller-accent focus:ring-seller-accent rounded-2xl">
                        <option value="orange" @selected(old('payment_mode', $sellerProfile->payment_mode) === 'orange')>Orange Money / MTN Money</option>
                        <option value="paypal" @selected(old('payment_mode', $sellerProfile->payment_mode) === 'paypal')>PayPal</option>
                        <option value="banque" @selected(old('payment_mode', $sellerProfile->payment_mode) === 'banque')>{{ __('Compte bancaire') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('payment_mode')" class="mt-2" />
                </div>

                <div x-show="mode === 'orange'" x-cloak>
                    <x-input-label for="numero_om" :value="__('Numéro de téléphone')" />
                    <x-text-input id="numero_om" name="numero_om" class="block mt-1 w-full" :value="old('numero_om', $sellerProfile->numero_om)" />
                    <x-input-error :messages="$errors->get('numero_om')" class="mt-2" />
                </div>

                <div x-show="mode === 'paypal'" x-cloak>
                    <x-input-label for="email_paypal" :value="__('Adresse email PayPal')" />
                    <x-text-input id="email_paypal" type="email" name="email_paypal" class="block mt-1 w-full" :value="old('email_paypal', $sellerProfile->email_paypal)" />
                    <x-input-error :messages="$errors->get('email_paypal')" class="mt-2" />
                </div>

                <div x-show="mode === 'banque'" x-cloak class="space-y-4">
                    <div>
                        <x-input-label for="nom_banque" :value="__('Nom de la banque')" />
                        <x-text-input id="nom_banque" name="nom_banque" class="block mt-1 w-full" :value="old('nom_banque', $sellerProfile->nom_banque)" />
                        <x-input-error :messages="$errors->get('nom_banque')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="numero_compte" :value="__('Numéro de compte')" />
                        <x-text-input id="numero_compte" name="numero_compte" class="block mt-1 w-full" :value="old('numero_compte', $sellerProfile->numero_compte)" />
                        <x-input-error :messages="$errors->get('numero_compte')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="titulaire_compte" :value="__('Nom du titulaire')" />
                        <x-text-input id="titulaire_compte" name="titulaire_compte" class="block mt-1 w-full" :value="old('titulaire_compte', $sellerProfile->titulaire_compte)" />
                        <x-input-error :messages="$errors->get('titulaire_compte')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
            </div>
        </form>

    </div>
</x-seller-layout>
