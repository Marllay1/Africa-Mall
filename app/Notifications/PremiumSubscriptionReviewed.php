<?php

namespace App\Notifications;

use App\Models\PremiumSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PremiumSubscriptionReviewed extends Notification
{
    use Queueable;

    public function __construct(private readonly PremiumSubscription $subscription)
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
        $isApproved = $this->subscription->status === 'active';

        $message = (new MailMessage)
            ->subject($isApproved ? __('Demande Premium approuvée') : __('Demande Premium refusée'))
            ->greeting($isApproved ? __('Votre demande Premium a été approuvée !') : __('Votre demande Premium a été refusée'))
            ->line(__('Formule :tier', ['tier' => $this->subscription->tierLabel()]));

        if (! $isApproved && $this->subscription->rejection_reason) {
            $message->line(__('Motif : :reason', ['reason' => $this->subscription->rejection_reason]));
        }

        return $message->action(__('Voir Premium'), route('seller.premium'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->subscription->status === 'active'
                ? __('Demande Premium approuvée')
                : __('Demande Premium refusée'),
            'body' => __('Formule :tier', ['tier' => $this->subscription->tierLabel()]),
            'url' => route('seller.premium'),
        ];
    }
}
