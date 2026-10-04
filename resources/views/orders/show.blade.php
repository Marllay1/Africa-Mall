<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('orders.index') }}" class="text-sm text-choco-soft hover:text-choco">&larr; {{ __('Mes commandes') }}</a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
                    @switch(session('status'))
                        @case('order-cancelled') {{ __('Votre commande a été annulée.') }} @break
                        @case('return-requested') {{ __('Votre demande de retour a été transmise. Un administrateur va l\'examiner.') }} @break
                    @endswitch
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-2xl border border-beige p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-lg font-semibold text-choco-dark">{{ __('Commande') }} #{{ $order->id }}</h1>
                        <p class="text-sm text-choco-soft">{{ $order->shop->name }} &middot; {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="text-sm capitalize bg-cream px-3 py-1 rounded-full text-choco-dark">{{ $order->status }}</span>
                </div>

                @if ($order->delivery_address)
                    <p class="text-sm text-choco-soft mb-4"><i class="fas fa-location-dot mr-1"></i>{{ __('Livraison :') }} {{ $order->delivery_address }}</p>
                @endif

                <div class="divide-y divide-beige border-t border-beige">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-choco-dark">{{ $item->product->name }}</p>
                                <p class="text-xs text-choco-soft">{{ $item->quantity }} &times; {{ number_format($item->unit_price, 0, ',', ' ') }} {{ $order->devise }}</p>
                            </div>
                            <p class="text-sm font-semibold text-choco-dark">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }} {{ $order->devise }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-beige mt-4">
                    <span class="font-semibold text-choco-dark">{{ __('Total') }}</span>
                    <span class="text-xl font-extrabold text-choco">{{ number_format($order->total, 0, ',', ' ') }} {{ $order->devise }}</span>
                </div>

                @if (in_array($order->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('orders.cancel', $order) }}" class="pt-4" onsubmit="return confirm('{{ __('Annuler cette commande ?') }}')">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-600 font-medium">{{ __('Annuler ma commande') }}</button>
                    </form>
                @elseif ($order->status === 'delivered' && $order->updated_at->diffInDays(now()) <= 14)
                    <div class="pt-4" x-data="{ open: false }">
                        <button type="button" @click="open = ! open" class="text-sm text-choco underline">{{ __('Demander un retour / remboursement') }}</button>
                        <form x-show="open" x-cloak method="POST" action="{{ route('orders.request-return', $order) }}" class="mt-3 space-y-2">
                            @csrf
                            <textarea name="reason" rows="2" required placeholder="{{ __('Pourquoi souhaitez-vous retourner cette commande ?') }}"
                                class="block w-full border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm text-sm"></textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-1" />
                            <x-secondary-button type="submit">{{ __('Envoyer la demande') }}</x-secondary-button>
                        </form>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
