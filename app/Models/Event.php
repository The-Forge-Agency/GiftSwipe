<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'birthday_person_name',
        'birthday_date',
        'owner_token',
        'wishlist_id',
    ];

    protected function casts(): array
    {
        return [
            'birthday_date' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                do {
                    $slug = strtolower(Str::random(8));
                } while (static::where('slug', $slug)->exists());

                $event->slug = $slug;
            }
        });
    }

    public function giftIdeas(): HasMany
    {
        return $this->hasMany(GiftIdea::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function swipes(): HasManyThrough
    {
        return $this->hasManyThrough(Swipe::class, Participant::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }
}
