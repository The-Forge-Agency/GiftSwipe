<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    protected $fillable = [
        'event_id',
        'author_name',
        'content',
        'author_token',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
