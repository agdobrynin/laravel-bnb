<?php

namespace Database\Seeders;

use App\Models\PersonAddress;
use Illuminate\Database\Seeder;

class PersonalAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        PersonAddress::factory(100)->create();
    }
}
