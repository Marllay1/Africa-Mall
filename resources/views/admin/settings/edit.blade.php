<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Paramètres plateforme') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-md p-4">
                @switch(session('status'))
                    @case('settings-updated') {{ __('Paramètres enregistrés.') }} @break
                    @case('test-email-sent') {{ __('Email de test envoyé. Vérifiez les logs ci-dessous si aucun fournisseur SMTP réel n\'est configuré.') }} @break
                    @case('test-email-failed') {{ __('L\'envoi a échoué — voir les logs pour le détail.') }} @break
                @endswitch
            </div>
        @endif

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-4">
            <h3 class="text-seller-sidebar font-semibold">{{ __('Plateforme & paiements') }}</h3>
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <label class="flex items-center gap-2 text-sm text-seller-sidebar">
                    <input type="checkbox" name="maintenance_mode" value="1" @checked($settings->maintenance_mode)>
                    {{ __('Mode maintenance (bloque Customer et Seller, l\'Admin garde l\'accès)') }}
                </label>

                <div>
                    <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Message affiché pendant la maintenance') }}</label>
                    <textarea name="maintenance_message" rows="2"
                        class="w-full border-[#ede3d3] focus:border-admin-accent focus:ring-admin-accent rounded-md shadow-sm text-sm">{{ old('maintenance_message', $settings->maintenance_message) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Montant minimum de retrait (XOF)') }}</label>
                        <input name="min_withdrawal_amount" type="number" min="1" value="{{ old('min_withdrawal_amount', $settings->min_withdrawal_amount) }}" required
                            class="w-full border-[#ede3d3] focus:border-admin-accent focus:ring-admin-accent rounded-md shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-[#7b5e47] mb-1">{{ __('Email de support affiché aux utilisateurs') }}</label>
                        <input name="support_email" type="email" value="{{ old('support_email', $settings->support_email) }}" placeholder="support@africamall.test"
                            class="w-full border-[#ede3d3] focus:border-admin-accent focus:ring-admin-accent rounded-md shadow-sm text-sm">
                    </div>
                </div>

                <button type="submit" class="px-5 py-2.5 bg-admin-accent hover:opacity-90 text-white font-semibold rounded-xl text-sm">
                    {{ __('Enregistrer') }}
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-3">
            <h3 class="text-seller-sidebar font-semibold">{{ __('Emails') }}</h3>
            <dl class="text-sm grid grid-cols-2 gap-2 max-w-sm">
                <dt class="text-[#7b5e47]">{{ __('Fournisseur actuel') }}</dt>
                <dd class="text-seller-sidebar font-medium">{{ $mailer }}</dd>
                <dt class="text-[#7b5e47]">{{ __('Adresse d\'expédition') }}</dt>
                <dd class="text-seller-sidebar font-medium">{{ $fromAddress }}</dd>
            </dl>
            @if ($mailer === 'log')
                <p class="text-xs text-[#a8815a]">{{ __('Aucun fournisseur SMTP réel configuré — les emails sont écrits dans les logs plutôt qu\'envoyés.') }}</p>
            @endif
            <form method="POST" action="{{ route('admin.settings.test-email') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-[#5e3e2b] hover:bg-seller-sidebar text-white text-sm rounded-md">
                    {{ __('Envoyer un email de test à mon adresse') }}
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-3">
            <h3 class="text-seller-sidebar font-semibold">{{ __('Journaux & sauvegardes') }}</h3>
            <p class="text-sm text-[#7b5e47]">{{ __('Consultez les dernières lignes du journal applicatif.') }}</p>
            <a href="{{ route('admin.settings.logs') }}" class="inline-block px-4 py-2 bg-[#5e3e2b] hover:bg-seller-sidebar text-white text-sm rounded-md">
                {{ __('Voir les logs') }}
            </a>
            <p class="text-xs text-[#a8815a] pt-2">{{ __('Sauvegardes automatiques, SMS, API et permissions granulaires : nécessitent le choix d\'un prestataire externe (stockage, fournisseur SMS) ou une décision produit — non configurés à ce stade.') }}</p>
        </div>

    </div>
</x-admin-layout>
