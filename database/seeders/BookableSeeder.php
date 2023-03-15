<?php

namespace Database\Seeders;

use App\Models\Bookable;
use App\Models\BookableCategory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BookableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = BookableCategory::all(['id']);

        Bookable::factory()
            ->count(100)
            ->state(new Sequence(
                fn (Sequence $sequence) => ['bookable_category_id' => $categories->random()->id],
            ))
            ->create();
    }
}
