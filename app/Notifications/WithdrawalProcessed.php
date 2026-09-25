<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WithdrawalProcessed extends Notification
{
    use Queueable;

    public function __construct(private readonly Withdrawal $withdrawal)
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
            'title' => $this->withdrawal->status === 'paid'
                ? __('Retrait payé')
                : __('Retrait refusé'),
            'body' => __(':amount :devise', [
                'amount' => number_format($this->withdrawal->amount, 0, ',', ' '),
                'devise' => $this->withdrawal->devise,
            ]),
            'url' => route('seller.revenues'),
        ];
    }
}
