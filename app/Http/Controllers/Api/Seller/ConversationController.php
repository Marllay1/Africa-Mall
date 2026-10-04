<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Concerns\InteractsWithConversationMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConversationController extends Controller
{
    use InteractsWithConversationMessages;

    public function index(Request $request): AnonymousResourceCollection
    {
        return ConversationResource::collection($this->conversationsList($request));
    }

    public function show(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorizeSeller($request, $conversation);

        $conversation->load('customer', 'messages.sender');
        $conversation->markReadFor($request->user());

        return response()->json([
            'conversation' => new ConversationResource($conversation),
            'messages' => MessageResource::collection($conversation->messages),
        ]);
    }

    public function send(Request $request, Conversation $conversation): MessageResource
    {
        $this->authorizeSeller($request, $conversation);

        return new MessageResource($this->storeMessage($request, $conversation));
    }

    private function conversationsList(Request $request)
    {
        return $this->shop($request)->conversations()
            ->with('customer', 'messages')
            ->get()
            ->sortByDesc(fn (Conversation $conversation) => $conversation->last_message_at ?? $conversation->created_at)
            ->values();
    }

    private function shop(Request $request): Shop
    {
        return $request->user()->sellerProfile->shop;
    }

    private function authorizeSeller(Request $request, Conversation $conversation): void
    {
        abort_unless($conversation->shop_id === $this->shop($request)->id, 403);
    }
}
