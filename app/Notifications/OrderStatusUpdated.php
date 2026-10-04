<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
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
        $number = str_pad((string) $this->order->id, 4, '0', STR_PAD_LEFT);

        return [
            'title' => __('Commande mise à jour'),
            'body' => __('Commande #AFR:number — :status', [
                'number' => $number,
                'status' => $this->order->status,
            ]),
            'url' => route('orders.show', $this->order),
        ];
    }
}
