<?php

namespace App\Notifications;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MessageReceived extends Notification
{
    use Queueable;

    public function __construct(private readonly Conversation $conversation)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('Nouveau message'),
            'body' => __('Vous avez reçu un nouveau message de :shop', ['shop' => $this->conversation->shop->name]),
            'url' => route('conversations.show', $this->conversation),
        ];
    }
}
