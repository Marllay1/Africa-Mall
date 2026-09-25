<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification
{
    use Queueable;

    public function __construct(private readonly Order $order)
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
            'title' => __('Nouvelle commande'),
            'body' => __('Commande #AFR:number — :total :devise', [
                'number' => str_pad((string) $this->order->id, 4, '0', STR_PAD_LEFT),
                'total' => number_format($this->order->total, 0, ',', ' '),
                'devise' => $this->order->devise,
            ]),
            'url' => route('seller.orders.index'),
        ];
    }
}
