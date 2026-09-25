<x-seller-layout>
    <x-slot name="header">
        <h2 class="text-seller-sidebar font-semibold text-xl">{{ __('Revenus') }}</h2>
    </x-slot>

    <div class="space-y-6">

        @if (session('status') === 'withdrawal-requested')
            <div class="bg-[#e7f0da] border border-[#d3e3c0] text-[#4b6b2c] text-sm rounded-2xl p-4">
                {{ __('Votre demande de retrait a été envoyée.') }}
            </div>
        @endif

        <div class="grid gap-5" style="grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));">
            <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
                <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Revenus Totaux') }}</h3>
                <h1 class="text-[28px] text-seller-sidebar mb-2.5">{{ number_format($balance['total'], 0, ',', ' ') }} {{ $shop->sellerProfile->devise }}</h1>
                <i class="fas fa-wallet absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
            </div>
            <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
                <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Disponible pour retrait') }}</h3>
                <h1 class="text-[28px] text-seller-sidebar mb-2.5">{{ number_format($balance['available'], 0, ',', ' ') }} {{ $shop->sellerProfile->devise }}</h1>
                <i class="fas fa-hand-holding-dollar absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
            </div>
            <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
                <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('En attente') }}</h3>
                <h1 class="text-[28px] text-seller-sidebar mb-2.5">{{ number_format($balance['pending'], 0, ',', ' ') }} {{ $shop->sellerProfile->devise }}</h1>
                <i class="fas fa-hourglass-half absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
            </div>
            <div class="bg-white p-6 rounded-[24px] shadow-[0_10px_25px_rgba(120,70,30,.08)] border border-[#f0e2d0] relative overflow-hidden">
                <h3 class="text-[#7b5e47] text-[15px] mb-2.5">{{ __('Déjà retiré') }}</h3>
                <h1 class="text-[28px] text-seller-sidebar mb-2.5">{{ number_format($balance['withdrawn'], 0, ',', ' ') }} {{ $shop->sellerProfile->devise }}</h1>
                <i class="fas fa-circle-check absolute right-5 top-5 text-[44px] text-seller-border opacity-25"></i>
            </div>
        </div>

        <div class="grid gap-5" style="grid-template-columns: 1fr 1fr;">
            <div class="bg-white rounded-[24px] p-6 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
                <h3 class="text-seller-sidebar font-semibold mb-1">{{ __('Demander un retrait') }}</h3>
                <p class="text-xs text-[#7b5e47] mb-4">
                    {{ __('Vers :method. Pour changer le moyen de retrait, rendez-vous dans les', ['method' => $payoutMethodLabel]) }}
                    <a href="{{ route('seller.settings') }}" class="underline">{{ __('Paramètres') }}</a>.
                </p>

                @if ($balance['available'] > 0)
                    <form method="POST" action="{{ route('seller.withdrawals.store') }}" class="flex items-start gap-3">
                        @csrf
                        <div class="flex-1">
                            <x-text-input type="number" name="amount" min="1" max="{{ $balance['available'] }}" class="block w-full" placeholder="{{ __('Montant') }}" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <x-primary-button>{{ __('Retirer') }}</x-primary-button>
                    </form>
                @else
                    <p class="text-sm text-[#7b5e47]">{{ __("Aucun solde disponible pour le moment.") }}</p>
                @endif

                <div class="mt-5 divide-y divide-[#f0e2d0]">
                    @forelse ($withdrawals as $withdrawal)
                        <div class="py-2.5 flex items-center justify-between text-sm">
                            <div>
                                <p class="text-seller-sidebar font-medium">{{ number_format($withdrawal->amount, 0, ',', ' ') }} {{ $withdrawal->devise }}</p>
                                <p class="text-xs text-[#7b5e47]">{{ $withdrawal->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if ($withdrawal->status === 'paid')
                                <span class="inline-block px-3 py-1 rounded-full text-[12px] font-semibold bg-[#e7f0da] text-[#4b6b2c]">{{ __('Payé') }}</span>
                            @elseif ($withdrawal->status === 'rejected')
                                <span class="inline-block px-3 py-1 rounded-full text-[12px] font-semibold bg-[#fce8e6] text-[#b34a3b]">{{ __('Refusé') }}</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-[12px] font-semibold bg-[#fdf1db] text-[#b47b3c]">{{ __('En attente') }}</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-[#7b5e47] py-2">{{ __('Aucune demande de retrait pour le moment.') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-6 shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
                <h3 class="text-seller-sidebar font-semibold mb-4">{{ __('Transactions') }}</h3>
                <div class="divide-y divide-[#f0e2d0] max-h-[420px] overflow-y-auto">
                    @forelse ($transactions as $transaction)
                        <div class="py-2.5 flex items-center justify-between text-sm">
                            <div>
                                <p class="text-seller-sidebar font-medium">{{ $transaction['label'] }}</p>
                                <p class="text-xs text-[#7b5e47]">{{ $transaction['date']->format('d/m/Y H:i') }}</p>
                            </div>
                            <p class="font-semibold {{ $transaction['type'] === 'withdrawal' ? 'text-[#b34a3b]' : 'text-[#4b6b2c]' }}">
                                {{ $transaction['type'] === 'withdrawal' ? '-' : '+' }}{{ number_format($transaction['amount'], 0, ',', ' ') }} {{ $transaction['devise'] }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-[#7b5e47] py-2">{{ __('Aucune transaction pour le moment.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-seller-layout>
