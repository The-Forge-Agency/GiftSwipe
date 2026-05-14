<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Wishlist extends Model
{
    /** @use HasFactory<\Database\Factories\WishlistFactory> */
    use HasFactory;

    protected $fillable = [
        'public_slug',
        'private_slug',
        'person_name',
        'birthday_date',
        'owner_token',
    ];

    protected function casts(): array
    {
        return [
            'birthday_date' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'public_slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Wishlist $wishlist) {
            if (empty($wishlist->public_slug)) {
                do {
                    $slug = strtolower(Str::random(8));
                } while (static::where('public_slug', $slug)->exists());
                $wishlist->public_slug = $slug;
            }

            if (empty($wishlist->private_slug)) {
                do {
                    $slug = strtolower(Str::random(12));
                } while (static::where('private_slug', $slug)->exists());
                $wishlist->private_slug = $slug;
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
