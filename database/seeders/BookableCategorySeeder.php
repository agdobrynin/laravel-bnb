<?php

namespace Database\Seeders;

use App\Models\BookableCategory;
use Illuminate\Database\Seeder;

class BookableCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = ['Room', 'Villa', 'House', 'Cottage', 'Fancy room', 'Luxury apartment'];

        foreach ($titles as $title) {
            $category = new BookableCategory();
            $category->name = $title;
            $category->save();
        }
    }
}
