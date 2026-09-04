@props(['conversation', 'messages', 'currentUserId', 'pollUrl', 'sendUrl', 'title', 'subtitle' => null])

<div
    x-data="chatThread({
        conversationId: {{ $conversation->id }},
        pollUrl: '{{ $pollUrl }}',
        sendUrl: '{{ $sendUrl }}',
        currentUserId: {{ $currentUserId }},
        initialMessages: {{ \Illuminate\Support\Js::from($messages->map(fn ($message) => [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'mine' => $message->sender_id === $currentUserId,
            'body' => $message->body,
            'image_url' => $message->image_url,
            'created_at' => $message->created_at->toIso8601String(),
        ])) }},
    })"
    class="bg-white shadow-sm rounded-2xl border border-beige flex flex-col h-[70vh]"
>
    <div class="px-4 py-3 border-b border-beige">
        <p class="font-semibold text-choco-dark">{{ $title }}</p>
        @if ($subtitle)
            <p class="text-xs text-choco-soft">{{ $subtitle }}</p>
        @endif
    </div>

    <div x-ref="scroller" class="flex-1 overflow-y-auto px-4 py-4 space-y-3">
        <template x-for="message in messages" :key="message.id">
            <div class="flex" :class="message.mine ? 'justify-end' : 'justify-start'">
                <div class="max-w-[75%] rounded-2xl px-3 py-2 text-sm"
                    :class="message.mine ? 'bg-choco text-white rounded-br-sm' : 'bg-cream text-choco-dark rounded-bl-sm'">
                    <template x-if="message.image_url">
                        <img :src="message.image_url" class="rounded-lg mb-1 max-h-48 object-cover">
                    </template>
                    <p x-show="message.body" x-text="message.body" class="whitespace-pre-wrap break-words"></p>
                    <p class="text-[10px] mt-1 opacity-70" :class="message.mine ? 'text-cream' : 'text-choco-soft'">
                        <span x-show="message.pending">{{ __('Envoi...') }}</span>
                        <span x-show="message.failed" class="text-red-300">{{ __('Échec') }}</span>
                    </p>
                </div>
            </div>
        </template>

        <p x-show="messages.length === 0" class="text-center text-sm text-choco-soft">{{ __('Aucun message pour le moment.') }}</p>
    </div>

    <form @submit.prevent="send" class="border-t border-beige p-3 space-y-2">
        <input type="text" x-model="imageUrl" placeholder="{{ __('URL image (optionnel)') }}"
            class="w-full text-xs border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm">
        <div class="flex items-end gap-2">
            <textarea x-model="body" rows="1" placeholder="{{ __('Votre message...') }}"
                @keydown.enter.prevent="send"
                class="flex-1 resize-none border-beige focus:border-choco focus:ring-choco rounded-md shadow-sm text-sm"></textarea>
            <button type="submit" :disabled="sending" class="px-4 py-2 bg-choco hover:bg-choco-light disabled:opacity-50 text-white text-sm rounded-md">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>
