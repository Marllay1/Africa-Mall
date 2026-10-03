<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isPaid = $this->withdrawal->status === 'paid';
        $amount = number_format($this->withdrawal->amount, 0, ',', ' ').' '.$this->withdrawal->devise;

        $message = (new MailMessage)
            ->subject($isPaid ? __('Retrait payé') : __('Retrait refusé'))
            ->greeting($isPaid ? __('Votre retrait a été payé') : __('Votre retrait a été refusé'))
            ->line(__('Montant : :amount', ['amount' => $amount]));

        if (! $isPaid && $this->withdrawal->rejection_reason) {
            $message->line(__('Motif : :reason', ['reason' => $this->withdrawal->rejection_reason]));
        }

        return $message->action(__('Voir mes revenus'), route('seller.revenues'));
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
