@props(['conversation', 'messages', 'currentUserId', 'pollUrl', 'sendUrl', 'title', 'subtitle' => null, 'bare' => false])

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
    @class([
        'bg-white overflow-hidden flex flex-col',
        'shadow-sm rounded-2xl border border-beige h-[70vh]' => ! $bare,
        'h-full flex-1' => $bare,
    ])
>
    @unless ($bare)
        <div class="px-4 py-3 border-b border-beige bg-white flex-shrink-0">
            <p class="font-semibold text-choco-dark">{{ $title }}</p>
            @if ($subtitle)
                <p class="text-xs text-choco-soft">{{ $subtitle }}</p>
            @endif
        </div>
    @endunless

    <div x-ref="scroller" class="flex-1 overflow-y-auto px-4 py-4 space-y-2.5 bg-[#ECE5DD]">
        <template x-for="message in messages" :key="message.id">
            <div class="flex" :class="message.mine ? 'justify-end' : 'justify-start'">
                <div class="max-w-[75%] rounded-xl px-3.5 py-2.5 text-sm"
                    :class="message.mine ? 'bg-[#DCF8C6] text-[#2E2418]' : 'bg-white text-[#2E2418]'">
                    <template x-if="message.image_url">
                        <img :src="message.image_url" class="rounded-lg mb-1 max-h-48 object-cover">
                    </template>
                    <p x-show="message.body" x-text="message.body" class="whitespace-pre-wrap break-words"></p>
                    <p class="text-[10px] mt-1 text-[#667] text-right">
                        <span x-show="message.pending">{{ __('Envoi...') }}</span>
                        <span x-show="message.failed" class="text-red-500">{{ __('Échec') }}</span>
                    </p>
                </div>
            </div>
        </template>

        <p x-show="messages.length === 0" class="text-center text-sm text-[#667]">{{ __('Aucun message pour le moment.') }}</p>
    </div>

    <form @submit.prevent="send" class="border-t border-beige p-3 space-y-2 bg-[#F0F0F0] flex-shrink-0">
        <input type="text" x-model="imageUrl" placeholder="{{ __('URL image (optionnel)') }}"
            class="w-full text-xs border-none rounded-full bg-white shadow-sm focus:ring-choco px-4">
        <div class="flex items-end gap-2">
            <textarea x-model="body" rows="1" placeholder="{{ __('Message...') }}"
                @keydown.enter.prevent="send"
                class="flex-1 resize-none border-none rounded-full bg-white shadow-sm focus:ring-choco text-sm px-4 py-2.5"></textarea>
            <button type="submit" :disabled="sending" class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-full bg-[#075E54] hover:bg-[#075E54]/90 disabled:opacity-50 text-white">
                <i class="fas fa-paper-plane text-sm"></i>
            </button>
        </div>
    </form>
</div>
