<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftIdea extends Model
{
    /** @use HasFactory<\Database\Factories\GiftIdeaFactory> */
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'url',
        'image_url',
        'description',
        'price',
        'added_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function swipes(): HasMany
    {
        return $this->hasMany(Swipe::class);
    }
}
