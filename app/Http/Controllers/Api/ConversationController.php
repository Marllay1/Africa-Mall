<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\InteractsWithConversationMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConversationController extends Controller
{
    use InteractsWithConversationMessages;

    public function index(Request $request): AnonymousResourceCollection
    {
        $conversations = $request->user()->conversations()
            ->with('shop', 'messages')
            ->get()
            ->sortByDesc(fn (Conversation $conversation) => $conversation->last_message_at ?? $conversation->created_at)
            ->values();

        return ConversationResource::collection($conversations);
    }

    public function show(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorizeCustomer($request, $conversation);

        $conversation->load('shop', 'messages.sender');
        $conversation->markReadFor($request->user());

        return response()->json([
            'conversation' => new ConversationResource($conversation),
            'messages' => MessageResource::collection($conversation->messages),
        ]);
    }

    public function startFromProduct(Request $request, Product $product): ConversationResource
    {
        $shop = $product->shop;

        abort_if($shop->sellerProfile->user_id === $request->user()->id, 403);

        $conversation = Conversation::where('shop_id', $shop->id)
            ->where('customer_id', $request->user()->id)
            ->first();

        if (! $conversation) {
            $conversation = new Conversation;
            $conversation->shop_id = $shop->id;
            $conversation->customer_id = $request->user()->id;
            $conversation->save();
        }

        return new ConversationResource($conversation->load('shop'));
    }

    public function send(Request $request, Conversation $conversation): MessageResource
    {
        $this->authorizeCustomer($request, $conversation);

        return new MessageResource($this->storeMessage($request, $conversation));
    }

    private function authorizeCustomer(Request $request, Conversation $conversation): void
    {
        abort_unless($conversation->customer_id === $request->user()->id, 403);
    }
}
