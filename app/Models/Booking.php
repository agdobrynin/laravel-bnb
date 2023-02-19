<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['start', 'end'];

    public function bookable(): BelongsTo
    {
        return $this->belongsTo(Bookable::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }


    public function scopeBetweenDates(Builder $builder, string $start, string $end): Builder
    {
        return $builder->where('end', '>=', $start)
            ->where('start', '<=', $end);
    }
}
