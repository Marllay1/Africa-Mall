<?php

namespace App\Notifications;

use App\Models\PremiumSubscription;
use Illuminate\Bus\Queueable;
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
        return ['database'];
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
