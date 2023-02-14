<?php

namespace Database\Seeders;

use App\Models\Bookable;
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
        Bookable::factory()->count(50)->create();
    }
}
