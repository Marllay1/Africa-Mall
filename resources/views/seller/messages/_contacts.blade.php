@php $activeId = $activeConversation->id ?? null; @endphp

<div class="w-full sm:w-[350px] flex-shrink-0 border-r border-[#f0e2d0] bg-[#fffaf4] flex flex-col {{ $activeId ? 'hidden sm:flex' : 'flex' }}">
    <div class="p-5 bg-[#f9f2e7] border-b border-[#f0e2d0]">
        <h3 class="text-seller-sidebar font-semibold">{{ __('Messages Clients') }}</h3>
        <div class="bg-white px-4 py-2.5 rounded-full flex items-center gap-2.5 border border-[#e0cfb5] mt-3">
            <i class="fas fa-search text-[#7b5e47] text-sm"></i>
            <input type="text" placeholder="{{ __('Recherche...') }}" class="border-none outline-none w-full bg-transparent p-0 text-sm focus:ring-0">
        </div>
    </div>

    <div class="flex-1 overflow-y-auto">
        @forelse ($conversations as $conversation)
            @php
                $last = $conversation->messages->last();
                $unread = $conversation->unreadCountFor(auth()->user());
            @endphp
            <a href="{{ route('seller.conversations.show', $conversation) }}"
                class="flex items-center gap-3 px-5 py-3.5 border-b border-[#faf0e6] {{ $activeId === $conversation->id ? 'bg-[#efe0d1]' : 'hover:bg-white' }}">
                <div class="w-[50px] h-[50px] rounded-full bg-seller-accent text-white flex items-center justify-center font-bold flex-shrink-0">
                    {{ mb_strtoupper(mb_substr($conversation->customer->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-seller-sidebar truncate">{{ $conversation->customer->name }}</h4>
                    <div class="text-xs text-[#7b5e47] truncate">{{ $last ? ($last->body ?: __('Image')) : __('Aucun message') }}</div>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-[11px] text-[#7b5e47]">{{ $last?->created_at->format('H:i') }}</span>
                    @if ($unread > 0)
                        <div class="bg-seller-accent text-white text-[11px] rounded-full w-5 h-5 flex items-center justify-center mt-1 ml-auto">{{ $unread }}</div>
                    @endif
                </div>
            </a>
        @empty
            <p class="text-center text-[#7b5e47] text-sm p-8">{{ __('Aucune conversation pour le moment.') }}</p>
        @endforelse
    </div>
</div>
