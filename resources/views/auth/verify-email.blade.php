<x-guest-layout>
    <div class="mb-4 text-sm text-choco-soft">
        {{ __('Merci de votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ? Si vous ne l\'avez pas reçu, nous pouvons vous en envoyer un autre avec plaisir.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Un nouveau lien de vérification a été envoyé à l\'adresse email fournie lors de l\'inscription.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Renvoyer l\'email de vérification') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-choco-soft hover:text-choco-dark rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold">
                {{ __('Déconnexion') }}
            </button>
        </form>
    </div>
</x-guest-layout>
