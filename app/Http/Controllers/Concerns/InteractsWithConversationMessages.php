<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait InteractsWithConversationMessages
{
    private function storeMessage(Request $request, Conversation $conversation): Message
    {
        $validated = $request->validate([
            'body' => ['nullable', 'string', 'max:2000'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        abort_if(empty($validated['body']) && empty($validated['image_url']), 422, 'Message vide.');

        $message = new Message($validated);
        $message->conversation_id = $conversation->id;
        $message->sender_id = $request->user()->id;
        $message->save();

        $conversation->last_message_at = $message->created_at;
        $conversation->save();

        return $message;
    }

    private function pollMessages(Request $request, Conversation $conversation): JsonResponse
    {
        $afterId = $request->integer('after_id', 0);

        $messages = $conversation->messages()->where('id', '>', $afterId)->with('sender')->get();

        $conversation->markReadFor($request->user());

        return response()->json([
            'messages' => $messages->map(fn (Message $message) => $this->formatMessage($message, $request->user()))->values(),
        ]);
    }

    private function formatMessage(Message $message, User $currentUser): array
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'mine' => $message->sender_id === $currentUser->id,
            'body' => $message->body,
            'image_url' => $message->image_url,
            'created_at' => $message->created_at->toIso8601String(),
        ];
    }
}
