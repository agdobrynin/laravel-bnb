<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bookable extends Model
{
    use HasFactory, HasUuids;

    public function booking(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function availableForDate(string $start, string $end): Collection
    {
        return $this->booking()->betweenDates($start, $end)->get();
    }
}
