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
                                <span class="text-xs text-choco-soft">{{ number_format($line['product']->effectivePrice(), 0, ',', ' ') }} {{ $line['product']->devise }} &times; {{ $line['quantity'] }}</span><br>
                                <strong class="text-choco text-sm">{{ number_format($line['line_total'], 0, ',', ' ') }} {{ $line['product']->devise }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (session('status'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm rounded-md p-4">
                        @switch(session('status'))
                            @case('coupon-applied') {{ __('Code promo appliqué.') }} @break
                            @case('coupon-invalid') {{ __('Ce code promo est invalide ou n\'est plus utilisable.') }} @break
                            @case('coupon-removed') {{ __('Code promo retiré.') }} @break
                        @endswitch
                    </div>
                @endif

                <div class="space-y-2">
                    @if ($coupon)
                        <div class="flex items-center justify-between bg-cream/60 border border-beige rounded-xl p-3 text-sm">
                            <span class="text-choco-dark">{{ __('Code :') }} <strong>{{ $coupon->code }}</strong> — {{ __('−:n XOF', ['n' => number_format($discount, 0, ',', ' ')]) }}</span>
                            <form method="POST" action="{{ route('cart.remove-coupon') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 text-xs">{{ __('Retirer') }}</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('cart.apply-coupon') }}" class="flex items-center gap-2">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="{{ __('Code promo') }}"
                                class="flex-1 border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm text-sm">
                            <x-secondary-button type="submit">{{ __('Appliquer') }}</x-secondary-button>
                        </form>
                    @endif
                </div>

                <div class="space-y-1">
                    <p class="text-sm text-choco-soft flex justify-between"><span>{{ __('Sous-total') }}</span><span>{{ number_format($subtotal, 0, ',', ' ') }} XOF</span></p>
                    @if ($discount > 0)
                        <p class="text-sm text-green-600 flex justify-between"><span>{{ __('Réduction') }}</span><span>−{{ number_format($discount, 0, ',', ' ') }} XOF</span></p>
                    @endif
                    <p class="font-bold text-choco text-xl flex justify-between"><span>{{ __('Total') }}</span><span>{{ number_format($total, 0, ',', ' ') }} XOF</span></p>
                </div>

                <div>
                    <h3 class="font-semibold text-choco-dark mb-3">{{ __('Moyen de paiement') }}</h3>
                    <p class="text-sm text-choco-soft mb-4">{{ __('Choisissez votre mode de paiement :') }}</p>

                    <form method="POST" action="{{ route('cart.checkout') }}" class="space-y-4">
                        @csrf
                        @foreach ($paymentMethods as $method)
                            <label class="flex items-center p-4 border-2 border-beige rounded-2xl cursor-pointer has-[:checked]:border-choco has-[:checked]:bg-cream/60 transition">
                                <input type="radio" name="payment_method" value="{{ $method->code }}" class="text-choco focus:ring-choco" @checked($loop->first)>
                                <span class="ml-3 mr-3">{{ $method->icon }}</span> {{ $method->label }}
                            </label>
                        @endforeach

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
