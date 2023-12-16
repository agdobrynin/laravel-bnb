<?php

namespace App\Models;

use App\Dto\BookablesFilterDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Builder Filter(BookablesFilterDto $filterDto): Builder
 * @method static Builder DisplayByFilterWithCategory(BookablesFilterDto $filterDto): Builder
 */
class Bookable extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'description',
        'price',
        'price_weekend',
        'bookable_category_id',
    ];

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

    public function scopePriceLowToHi(Builder $builder): Builder
    {
        return $builder->orderBy('price');
    }

    public function scopeFilter(Builder $builder, BookablesFilterDto $filterDto): Builder
    {
        return $builder->when(
            $filterDto->bookableCategoryId,
            fn (Builder $query, $value) => $query->where('bookable_category_id', $value)
        )->when(
            $filterDto->priceMin,
            fn (Builder $query, $value) => $query->where('price', '>=', $value)
        )->when(
            $filterDto->priceMax,
            fn (Builder $query, $value) => $query->where('price', '<=', $value)
        )->when(
            $filterDto->priceWeekendMin,
            fn (Builder $query, $value) => $query->where('price_weekend', '>=', $value)
        )->when(
            $filterDto->priceWeekendMax,
            fn (Builder $query, $value) => $query->where('price_weekend', '<=', $value)
        );
    }

    public static function scopeDisplayByFilterWithCategory(Builder $builder, BookablesFilterDto $filterDto)
    {
        return static::filter($filterDto)
            ->with('bookableCategory')
            ->priceLowToHi()
            ->latest();
    }
}
