<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bookable extends Model
{
    use HasFactory, HasUuids;

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookableCategory(): BelongsTo
    {
        return $this->belongsTo(BookableCategory::class)->withDefault();
    }
    public function availableForDate(string $start, string $end): Collection
    {
        return $this->bookings()->betweenDates($start, $end)->get();
    }
}
