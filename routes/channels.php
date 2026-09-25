<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function (User $user, int $conversationId) {
    $conversation = Conversation::with('shop.sellerProfile')->find($conversationId);

    if (! $conversation) {
        return false;
    }

    return $conversation->customer_id === $user->id
        || $conversation->shop->sellerProfile->user_id === $user->id
        || $conversation->hasParticipant($user);
});
