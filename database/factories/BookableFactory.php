<?php

namespace Database\Factories;

use App\Models\Bookable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookable>
 */
class BookableFactory extends Factory
{
    protected $model = Bookable::class;

    public function definition(): array
    {
        $regularPrice = rand(5, 100);
        $weekendPrice = (int) ($regularPrice + ($regularPrice * 0.15));

        return [
            'title' => fake()->city,
            'description' => fake()->realTextBetween(100, 300),
            'price' => $regularPrice,
            'price_weekend' => $weekendPrice,
        ];
    }
}
