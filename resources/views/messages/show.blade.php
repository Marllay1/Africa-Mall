<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex h-[calc(100vh-230px)] min-h-[420px] bg-white rounded-[24px] overflow-hidden shadow-sm border border-beige">
                @include('messages._list', ['conversations' => $conversations, 'activeConversation' => $conversation])

                <div class="flex-1 flex flex-col">
                    <div class="px-5 py-3.5 border-b border-beige flex items-center gap-3 flex-shrink-0 bg-white">
                        <a href="{{ route('conversations.index') }}" class="sm:hidden text-choco-soft hover:text-choco">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="w-[42px] h-[42px] rounded-full bg-choco-dark text-white flex items-center justify-center font-bold flex-shrink-0">
                            {{ mb_strtoupper(mb_substr($conversation->shop->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-choco-dark truncate">{{ $conversation->shop->name }}</h4>
                            @if ($conversation->shop->isPremium())
                                <p class="text-xs text-choco-soft"><i class="fas fa-star text-gold"></i> {{ __('Boutique Premium') }}</p>
                            @endif
                        </div>
                    </div>

                    <x-chat-thread
                        :conversation="$conversation"
                        :messages="$messages"
                        :current-user-id="auth()->id()"
                        :poll-url="route('conversations.poll', $conversation)"
                        :send-url="route('conversations.send', $conversation)"
                        :title="$conversation->shop->name"
                        :bare="true"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
