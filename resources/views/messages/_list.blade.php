@php $activeId = $activeConversation->id ?? null; @endphp

<div x-data="{ search: '' }"
    class="w-full sm:w-[340px] flex-shrink-0 border-r border-beige bg-white flex flex-col {{ $activeId ? 'hidden sm:flex' : 'flex' }}">
    <div class="p-5 border-b border-beige">
        <h3 class="text-choco-dark font-semibold">{{ __('Messages') }}</h3>
        <div class="bg-cream px-4 py-2.5 rounded-full flex items-center gap-2.5 border border-beige mt-3">
            <i class="fas fa-magnifying-glass text-choco-soft text-sm"></i>
            <input type="text" x-model="search" placeholder="{{ __('Rechercher une conversation...') }}"
                class="border-none outline-none w-full bg-transparent p-0 text-sm focus:ring-0">
        </div>
    </div>

    <div class="flex-1 overflow-y-auto">
        @forelse ($conversations as $conversation)
            @php
                $last = $conversation->messages->last();
                $unread = $conversation->unreadCountFor(auth()->user());
                $preview = $last ? ($last->body ?: __('Image')) : __('Aucun message');
            @endphp
            <a href="{{ route('conversations.show', $conversation) }}"
                x-show="search === '' || {{ \Illuminate\Support\Js::from(mb_strtolower($conversation->shop->name.' '.$preview)) }}.includes(search.toLowerCase())"
                class="flex items-center gap-3 px-5 py-3.5 border-b border-beige/60 {{ $activeId === $conversation->id ? 'bg-cream' : 'hover:bg-cream/60' }} transition">
                <div class="w-[50px] h-[50px] rounded-full bg-choco-dark text-white flex items-center justify-center font-bold flex-shrink-0">
                    {{ mb_strtoupper(mb_substr($conversation->shop->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-choco-dark truncate">{{ $conversation->shop->name }}</h4>
                    <div class="text-xs text-choco-soft truncate {{ $unread > 0 ? 'font-semibold text-choco-dark' : '' }}">{{ $preview }}</div>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-[11px] text-choco-soft">{{ $last?->created_at->diffForHumans() }}</span>
                    @if ($unread > 0)
                        <div class="bg-choco text-white text-[11px] font-bold rounded-full w-5 h-5 flex items-center justify-center mt-1 ml-auto">{{ $unread }}</div>
                    @endif
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center text-center py-14 px-6">
                <div class="w-16 h-16 rounded-full bg-cream flex items-center justify-center mb-4">
                    <i class="fas fa-comments text-2xl text-choco-soft"></i>
                </div>
                <h3 class="text-base font-bold text-choco-dark mb-1.5">{{ __('Aucune conversation') }}</h3>
                <p class="text-sm text-choco-soft mb-5 max-w-[220px]">{{ __('Contactez un vendeur depuis une fiche produit pour démarrer une conversation.') }}</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-choco hover:bg-choco-light text-white font-semibold text-sm px-5 py-2.5 rounded-full transition">
                    <i class="fas fa-store"></i> {{ __('Parcourir les produits') }}
                </a>
            </div>
        @endforelse
    </div>
</div>
