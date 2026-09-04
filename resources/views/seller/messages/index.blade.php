<x-seller-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-choco-dark leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl space-y-4">
        @if ($conversations->isEmpty())
            <div class="bg-white shadow-sm rounded-2xl border border-beige p-10 text-center text-choco-soft">
                {{ __('Aucune conversation pour le moment.') }}
            </div>
        @else
            <div class="bg-white shadow-sm rounded-2xl border border-beige divide-y divide-beige">
                @foreach ($conversations as $conversation)
                    @php
                        $last = $conversation->messages->last();
                        $unread = $conversation->unreadCountFor(auth()->user());
                    @endphp
                    <a href="{{ route('seller.conversations.show', $conversation) }}" class="flex items-center gap-3 p-4 hover:bg-cream transition">
                        <div class="w-11 h-11 rounded-full bg-choco-dark text-white flex items-center justify-center font-bold flex-shrink-0">
                            {{ mb_strtoupper(mb_substr($conversation->customer->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-choco-dark truncate">{{ $conversation->customer->name }}</p>
                            <p class="text-xs text-choco-soft truncate">
                                {{ $last ? ($last->body ?: __('Image')) : __('Aucun message') }}
                            </p>
                        </div>
                        @if ($unread > 0)
                            <span class="bg-choco text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0">{{ $unread }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-seller-layout>
