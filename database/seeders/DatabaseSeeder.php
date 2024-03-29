<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BookableCategorySeeder::class,
        ]);

        $this->call([
            BookableSeeder::class,
            PersonalAddressSeeder::class,
            BookingDatesSeeder::class,
            ReviewForBookablesSeeder::class,
        ]);
    }
}
