<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipantFactory> */
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'budget_max',
        'has_finished_swiping',
    ];

    protected function casts(): array
    {
        return [
            'budget_max' => 'decimal:2',
            'has_finished_swiping' => 'boolean',
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
