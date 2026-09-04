<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('cart.show') }}" class="text-sm text-choco-soft hover:text-choco">&larr; {{ __('Retour au panier') }}</a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl sm:rounded-3xl border border-beige p-6 sm:p-8 space-y-6">
                <h2 class="text-choco font-bold text-xl">{{ __('Récapitulatif de commande') }}</h2>

                <div class="divide-y divide-beige">
                    @foreach ($lines as $line)
                        <div class="flex gap-3 py-3">
                            <div class="w-14 h-14 bg-cream rounded-xl overflow-hidden flex-shrink-0">
                                @if ($line['product']->image_url)
                                    <img src="{{ $line['product']->image_url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1">
                                <strong class="text-choco-dark text-sm">{{ $line['product']->name }}</strong><br>
                                <span class="text-xs text-choco-soft">{{ number_format($line['product']->price, 0, ',', ' ') }} {{ $line['product']->devise }} &times; {{ $line['quantity'] }}</span><br>
                                <strong class="text-choco text-sm">{{ number_format($line['line_total'], 0, ',', ' ') }} {{ $line['product']->devise }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="font-bold text-choco text-xl">{{ __('Total') }} : {{ number_format($total, 0, ',', ' ') }} XOF</p>

                <div>
                    <h3 class="font-semibold text-choco-dark mb-3">{{ __('Moyen de paiement') }}</h3>
                    <p class="text-sm text-choco-soft mb-4">{{ __('Choisissez votre mode de paiement :') }}</p>

                    <form method="POST" action="{{ route('cart.checkout') }}" class="space-y-4">
                        @csrf
                        <label class="flex items-center p-4 border-2 border-beige rounded-2xl cursor-pointer has-[:checked]:border-choco has-[:checked]:bg-cream/60 transition">
                            <input type="radio" name="payment_method" value="mobile_money" class="text-choco focus:ring-choco" checked>
                            <i class="fas fa-mobile-alt text-choco ml-3 mr-3"></i> {{ __('Mobile Money') }}
                        </label>
                        <label class="flex items-center p-4 border-2 border-beige rounded-2xl cursor-pointer has-[:checked]:border-choco has-[:checked]:bg-cream/60 transition">
                            <input type="radio" name="payment_method" value="carte" class="text-choco focus:ring-choco">
                            <i class="fas fa-credit-card text-choco ml-3 mr-3"></i> {{ __('Carte bancaire') }}
                        </label>
                        <label class="flex items-center p-4 border-2 border-beige rounded-2xl cursor-pointer has-[:checked]:border-choco has-[:checked]:bg-cream/60 transition">
                            <input type="radio" name="payment_method" value="livraison" class="text-choco focus:ring-choco">
                            <i class="fas fa-truck text-choco ml-3 mr-3"></i> {{ __('Paiement à la livraison') }}
                        </label>

                        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />

                        <button type="submit" class="w-full bg-choco hover:bg-choco-light text-white font-bold py-3.5 rounded-full">
                            {{ __('Confirmer la commande') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
