<x-seller-layout>
    <div class="flex h-[calc(100vh-180px)] min-h-[420px] bg-white rounded-[24px] overflow-hidden shadow-[0_10px_25px_rgba(120,70,30,.07)] border border-[#f0e2d0]">
        @include('seller.messages._contacts', ['activeConversation' => $conversation])

        <div class="flex-1 flex flex-col">
            <div class="px-5 py-3.5 bg-[#f0e2d0] flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('seller.conversations.index') }}" class="sm:hidden text-seller-sidebar">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="w-[42px] h-[42px] rounded-full bg-seller-accent text-white flex items-center justify-center font-bold flex-shrink-0">
                    {{ mb_strtoupper(mb_substr($conversation->customer->name, 0, 1)) }}
                </div>
                <h4 class="font-semibold text-seller-sidebar">{{ $conversation->customer->name }}</h4>
            </div>

            <x-chat-thread
                :conversation="$conversation"
                :messages="$messages"
                :current-user-id="auth()->id()"
                :poll-url="route('seller.conversations.poll', $conversation)"
                :send-url="route('seller.conversations.send', $conversation)"
                :title="$conversation->customer->name"
                :bare="true"
            />
        </div>
    </div>
</x-seller-layout>
