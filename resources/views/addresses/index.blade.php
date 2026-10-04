<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Mes adresses') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
                    @switch(session('status'))
                        @case('address-added') {{ __('Adresse ajoutée.') }} @break
                        @case('address-updated') {{ __('Adresse mise à jour.') }} @break
                        @case('address-deleted') {{ __('Adresse supprimée.') }} @break
                    @endswitch
                </div>
            @endif

            <div class="space-y-3">
                @forelse ($addresses as $address)
                    <div class="bg-white shadow-sm rounded-2xl border border-beige p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-choco-dark">
                                    {{ $address->label }}
                                    @if ($address->is_default)
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-cream text-choco">{{ __('Par défaut') }}</span>
                                    @endif
                                </p>
                                <p class="text-sm text-choco-soft mt-1">{{ $address->recipient_name }} &middot; {{ $address->phone }}</p>
                                <p class="text-sm text-choco-soft">{{ $address->formatted() }}</p>
                            </div>
                            <form method="POST" action="{{ route('addresses.destroy', $address) }}" onsubmit="return confirm('{{ __('Supprimer cette adresse ?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-600">{{ __('Supprimer') }}</button>
                            </form>
                        </div>
                        @unless ($address->is_default)
                            <form method="POST" action="{{ route('addresses.update', $address) }}" class="mt-3">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="label" value="{{ $address->label }}">
                                <input type="hidden" name="recipient_name" value="{{ $address->recipient_name }}">
                                <input type="hidden" name="phone" value="{{ $address->phone }}">
                                <input type="hidden" name="address_line" value="{{ $address->address_line }}">
                                <input type="hidden" name="city" value="{{ $address->city }}">
                                <input type="hidden" name="is_default" value="1">
                                <button type="submit" class="text-sm text-choco underline">{{ __('Définir par défaut') }}</button>
                            </form>
                        @endunless
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-2xl border border-beige p-8 text-center text-choco-soft">
                        {{ __("Vous n'avez pas encore enregistré d'adresse.") }}
                    </div>
                @endforelse
            </div>

            <div class="bg-white shadow-sm rounded-2xl border border-beige p-6">
                <h3 class="font-semibold text-choco-dark mb-3">{{ __('Ajouter une adresse') }}</h3>
                <form method="POST" action="{{ route('addresses.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <x-input-label for="label" :value="__('Nom (ex. Domicile, Bureau)')" />
                        <x-text-input id="label" name="label" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label for="recipient_name" :value="__('Nom du destinataire')" />
                        <x-text-input id="recipient_name" name="recipient_name" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Téléphone')" />
                        <x-text-input id="phone" name="phone" type="tel" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label for="address_line" :value="__('Adresse')" />
                        <x-text-input id="address_line" name="address_line" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label for="city" :value="__('Ville (optionnel)')" />
                        <x-text-input id="city" name="city" class="block mt-1 w-full" />
                    </div>
                    <label class="flex items-center gap-2 text-sm text-choco-soft">
                        <input type="checkbox" name="is_default" value="1">
                        {{ __('Définir comme adresse par défaut') }}
                    </label>
                    <x-primary-button type="submit">{{ __('Enregistrer') }}</x-primary-button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
