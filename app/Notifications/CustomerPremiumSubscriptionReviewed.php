<?php

namespace App\Notifications;

use App\Models\CustomerPremiumSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerPremiumSubscriptionReviewed extends Notification
{
    use Queueable;

    public function __construct(private readonly CustomerPremiumSubscription $subscription)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable->email_notifications_enabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isApproved = $this->subscription->status === 'active';

        $message = (new MailMessage)
            ->subject($isApproved ? __('Demande AfricaMall Premium approuvée') : __('Demande AfricaMall Premium refusée'))
            ->greeting($isApproved ? __('Votre demande Premium a été approuvée !') : __('Votre demande Premium a été refusée'));

        if (! $isApproved && $this->subscription->rejection_reason) {
            $message->line(__('Motif : :reason', ['reason' => $this->subscription->rejection_reason]));
        }

        return $message->action(__('Voir Premium'), route('premium.show'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->subscription->status === 'active'
                ? __('Demande AfricaMall Premium approuvée')
                : __('Demande AfricaMall Premium refusée'),
            'body' => $this->subscription->status === 'active'
                ? __('Vous bénéficiez désormais des avantages Premium.')
                : __('Votre demande a été refusée.'),
            'url' => route('premium.show'),
        ];
    }
}
