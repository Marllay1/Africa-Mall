<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('conversations.index') }}" class="text-choco-soft hover:text-choco">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-choco-dark leading-tight">
                {{ $conversation->shop->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-chat-thread
                :conversation="$conversation"
                :messages="$messages"
                :current-user-id="auth()->id()"
                :poll-url="route('conversations.poll', $conversation)"
                :send-url="route('conversations.send', $conversation)"
                :title="$conversation->shop->name"
            />
        </div>
    </div>
</x-app-layout>
