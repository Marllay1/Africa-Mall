<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([])]
class Conversation extends Model
{
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('id');
    }

    /**
     * Extra participants beyond the customer/seller pair (e.g. an Admin invited to mediate).
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')->withPivot('role', 'joined_at');
    }

    public function hasParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function lastMessage(): ?Message
    {
        return $this->messages()->latest('id')->first();
    }

    public function unreadCountFor(User $user): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function markReadFor(User $user): void
    {
        $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
