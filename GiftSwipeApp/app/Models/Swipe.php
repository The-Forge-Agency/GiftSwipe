<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Swipe extends Model
{
    /** @use HasFactory<\Database\Factories\SwipeFactory> */
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'gift_idea_id',
        'liked',
    ];

    protected function casts(): array
    {
        return [
            'liked' => 'boolean',
        ];
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function giftIdea(): BelongsTo
    {
        return $this->belongsTo(GiftIdea::class);
    }
}
