<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithConversationMessages;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    use InteractsWithConversationMessages;

    public function index(Request $request): View
    {
        $conversations = $request->user()->conversations()
            ->with('shop', 'messages')
            ->get()
            ->sortByDesc(fn (Conversation $conversation) => $conversation->last_message_at ?? $conversation->created_at)
            ->values();

        return view('messages.index', [
            'conversations' => $conversations,
        ]);
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeCustomer($request, $conversation);

        $conversation->load('shop', 'messages.sender');
        $conversation->markReadFor($request->user());

        return view('messages.show', [
            'conversation' => $conversation,
            'messages' => $conversation->messages,
        ]);
    }

    public function startFromProduct(Request $request, Product $product): RedirectResponse
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

        return redirect()->route('conversations.show', $conversation);
    }

    public function send(Request $request, Conversation $conversation): RedirectResponse|JsonResponse
    {
        $this->authorizeCustomer($request, $conversation);

        $message = $this->storeMessage($request, $conversation);

        if ($request->expectsJson()) {
            return response()->json(['message' => $this->formatMessage($message, $request->user())]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorizeCustomer($request, $conversation);

        return $this->pollMessages($request, $conversation);
    }

    public function badge(Request $request): JsonResponse
    {
        $count = Message::whereIn('conversation_id', $request->user()->conversations()->pluck('id'))
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    private function authorizeCustomer(Request $request, Conversation $conversation): void
    {
        abort_unless($conversation->customer_id === $request->user()->id, 403);
    }
}
