<x-seller-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('seller.conversations.index') }}" class="text-choco-soft hover:text-choco">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-choco-dark leading-tight">
                {{ $conversation->customer->name }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <x-chat-thread
            :conversation="$conversation"
            :messages="$messages"
            :current-user-id="auth()->id()"
            :poll-url="route('seller.conversations.poll', $conversation)"
            :send-url="route('seller.conversations.send', $conversation)"
            :title="$conversation->customer->name"
        />
    </div>
</x-seller-layout>
