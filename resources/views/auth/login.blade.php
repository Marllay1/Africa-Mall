@php $portal = $portal ?? 'customer'; @endphp

<x-guest-layout>
    <div class="mb-5 text-center">
        <h1 class="text-lg font-bold text-choco-dark">
            @switch($portal)
                @case('seller') {{ __('Connexion Seller Center') }} @break
                @case('admin') {{ __('Connexion Administration AfricaMall') }} @break
                @default {{ __('Connexion') }}
            @endswitch
        </h1>
        @if ($portal === 'seller')
            <p class="text-sm text-choco-soft mt-1">{{ __('Accédez à votre espace AfricaMall Business.') }}</p>
        @elseif ($portal === 'admin')
            <p class="text-sm text-choco-soft mt-1">{{ __('Accès réservé aux administrateurs AfricaMall.') }}</p>
        @endif
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route($portal === 'customer' ? 'login' : $portal.'.login.store') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-choco shadow-sm focus:ring-gold" name="remember">
                <span class="ms-2 text-sm text-choco-soft">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-choco-soft hover:text-choco-dark rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Se connecter') }}
            </x-primary-button>
        </div>

        @if ($portal === 'customer')
            <p class="text-center text-sm text-choco-soft mt-5">
                {{ __('Pas encore de compte ?') }}
                <a href="{{ route('register') }}" class="text-choco font-semibold underline">{{ __('Créer un compte') }}</a>
            </p>
        @endif

        @if ($portal !== 'customer')
            <p class="text-center text-xs text-choco-soft mt-5">
                <a href="{{ route('login') }}" class="underline">{{ __('Vous êtes client ? Connexion Customer') }}</a>
            </p>
        @endif
    </form>
</x-guest-layout>
