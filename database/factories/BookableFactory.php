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
        return [
            'title' => fake()->city,
            'description' => fake()->realTextBetween(100, 300),
        ];
    }
}
