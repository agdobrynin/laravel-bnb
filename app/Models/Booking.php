<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * App\Models\File
 *
 * @method static Builder withoutReviewByUser(User $user): Builder
 */
class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['start', 'end', 'price'];

    public function bookable(): BelongsTo
    {
        return $this->belongsTo(Bookable::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function personAddress(): BelongsTo
    {
        return $this->belongsTo(PersonAddress::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function scopeBetweenDates(Builder $builder, string $start, string $end): Builder
    {
        return $builder->where('end', '>=', $start)
            ->where('start', '<=', $end);
    }

    public static function findByReviewKey(string $reviewKey): ?Booking
    {
        return static::where('review_key', $reviewKey)->with(['bookable', 'user'])->get()->first();
    }

    public function scopeWithoutReviewByUser(Builder $builder, User $user): Builder
    {
        return $builder->where('review_key', '!=', '')
            ->where('user_id', $user->id)
            ->with('bookable.bookableCategory')
            ->orderBy('start');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn (Booking $booking) => $booking->review_key = Str::uuid());
    }
}
