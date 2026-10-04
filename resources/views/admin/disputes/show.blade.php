<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-seller-sidebar leading-tight">
            {{ __('Dossier litige') }} — #AFR{{ str_pad($dispute->order->id, 4, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-md p-4">
                @switch(session('status'))
                    @case('dispute-notes-saved') {{ __('Notes enregistrées.') }} @break
                    @case('dispute-resolved') {{ __('Litige traité.') }} @break
                @endswitch
            </div>
        @endif

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-3">
            <h3 class="text-seller-sidebar font-semibold">{{ __('Commande') }}</h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <dt class="text-[#7b5e47]">{{ __('Client') }}</dt>
                    <dd class="text-seller-sidebar">{{ $dispute->order->user?->name ?? $dispute->order->guest_name.' ('.__('invité').')' }}</dd>
                </div>
                <div>
                    <dt class="text-[#7b5e47]">{{ __('Boutique') }}</dt>
                    <dd class="text-seller-sidebar">{{ $dispute->order->shop->name }}</dd>
                </div>
                <div>
                    <dt class="text-[#7b5e47]">{{ __('Total') }}</dt>
                    <dd class="text-seller-sidebar">{{ number_format($dispute->order->total, 0, ',', ' ') }} {{ $dispute->order->devise }}</dd>
                </div>
                <div>
                    <dt class="text-[#7b5e47]">{{ __('Statut commande') }}</dt>
                    <dd class="text-seller-sidebar capitalize">{{ $dispute->order->status }}</dd>
                </div>
            </dl>
            <div class="pt-2">
                <h4 class="text-[#7b5e47] text-sm mb-1">{{ __('Articles') }}</h4>
                <ul class="text-sm text-seller-sidebar space-y-1">
                    @foreach ($dispute->order->items as $item)
                        <li>{{ $item->quantity }} × {{ $item->product->name ?? __('Produit supprimé') }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-3">
            <h3 class="text-seller-sidebar font-semibold">{{ __('Messages (lecture seule)') }}</h3>
            @if ($conversation)
                <div class="space-y-2 max-h-72 overflow-y-auto text-sm">
                    @foreach ($conversation->messages as $message)
                        <div class="p-2 rounded-md {{ $message->sender_id === $dispute->order->user_id ? 'bg-[#f3e7d9]' : 'bg-admin-bg' }}">
                            <span class="text-[#7b5e47] text-xs">{{ $message->sender->name }} · {{ $message->created_at->format('d/m/Y H:i') }}</span>
                            <p class="text-seller-sidebar">{{ $message->body }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-[#a8815a] text-sm">{{ __('Aucune conversation entre ce client et cette boutique.') }}</p>
            @endif
        </div>

        <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-3">
            <h3 class="text-seller-sidebar font-semibold">{{ __('Notes internes / preuves') }}</h3>
            <form method="POST" action="{{ route('admin.disputes.update-notes', $dispute) }}" class="space-y-3">
                @csrf
                @method('PATCH')
                <textarea name="admin_notes" rows="4" placeholder="{{ __('Éléments recueillis (captures, échanges hors plateforme, etc.)') }}"
                    class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm">{{ old('admin_notes', $dispute->admin_notes) }}</textarea>
                <button type="submit" class="px-4 py-2 bg-[#5e3e2b] hover:bg-seller-sidebar text-white text-sm rounded-md">{{ __('Enregistrer les notes') }}</button>
            </form>
        </div>

        @if ($dispute->isOpen())
            <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-3">
                <h3 class="text-seller-sidebar font-semibold">{{ __('Décision') }}</h3>
                <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}" class="space-y-3">
                    @csrf
                    <div class="flex items-center gap-4 text-sm text-seller-sidebar">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="resolution" value="refunded" required> {{ __('Rembourser le client') }}
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="resolution" value="rejected" required> {{ __('Rejeter la demande') }}
                        </label>
                    </div>
                    <textarea name="resolution_notes" rows="3" required placeholder="{{ __('Motif de la décision') }}"
                        class="w-full bg-admin-bg border border-[#ede3d3] rounded-md text-seller-sidebar px-3 py-2 text-sm"></textarea>
                    @if ($dispute->order->shop->sellerProfile?->isActive())
                        <label class="flex items-center gap-2 text-sm text-seller-sidebar">
                            <input type="checkbox" name="sanction_seller" value="1">
                            {{ __('Suspendre le vendeur (sanction)') }}
                        </label>
                    @endif
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm rounded-md">{{ __('Clôturer le dossier') }}</button>
                </form>
            </div>
        @else
            <div class="bg-white rounded-[20px] shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0] p-6 space-y-2">
                <h3 class="text-seller-sidebar font-semibold">{{ __('Dossier clôturé') }}</h3>
                <p class="text-sm text-seller-sidebar">{{ $dispute->resolution === 'refunded' ? __('Client remboursé.') : __('Demande rejetée.') }}</p>
                <p class="text-sm text-[#7b5e47]">{{ $dispute->resolution_notes }}</p>
            </div>
        @endif

    </div>
</x-admin-layout>
