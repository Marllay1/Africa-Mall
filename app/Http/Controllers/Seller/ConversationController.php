<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Concerns\InteractsWithConversationMessages;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    use InteractsWithConversationMessages;

    public function index(Request $request): View
    {
        return view('seller.messages.index', [
            'conversations' => $this->conversationsList($request),
        ]);
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeSeller($request, $conversation);

        $conversation->load('customer', 'messages.sender');
        $conversation->markReadFor($request->user());

        return view('seller.messages.show', [
            'conversation' => $conversation,
            'messages' => $conversation->messages,
            'conversations' => $this->conversationsList($request),
        ]);
    }

    private function conversationsList(Request $request)
    {
        return $this->shop($request)->conversations()
            ->with('customer', 'messages')
            ->get()
            ->sortByDesc(fn (Conversation $conversation) => $conversation->last_message_at ?? $conversation->created_at)
            ->values();
    }

    public function send(Request $request, Conversation $conversation): RedirectResponse|JsonResponse
    {
        $this->authorizeSeller($request, $conversation);

        $message = $this->storeMessage($request, $conversation);

        if ($request->expectsJson()) {
            return response()->json(['message' => $this->formatMessage($message, $request->user())]);
        }

        return redirect()->route('seller.conversations.show', $conversation);
    }

    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorizeSeller($request, $conversation);

        return $this->pollMessages($request, $conversation);
    }

    public function badge(Request $request): JsonResponse
    {
        $count = Message::whereIn('conversation_id', $this->shop($request)->conversations()->pluck('id'))
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
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
