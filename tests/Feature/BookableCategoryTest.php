<?php

namespace Tests\Feature;

use App\Models\BookableCategory;
use Database\Seeders\BookableCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookableCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testEmptyCategory()
    {
        $response = $this->getJson('/api/bookables/categories');
        $response->assertOk()
            ->assertJsonStructure([
                'data'
            ])
            ->assertJsonCount(0, 'data');
    }

    public function testCategory()
    {
        $this->seed(BookableCategorySeeder::class);
        $categories = BookableCategory::all();

        $response = $this->getJson('/api/bookables/categories');
        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'name']]
            ])
            ->assertJsonCount($categories->count(), 'data')
            ->assertJson(
                ['data' =>
                    [
                        [
                            'id' => $categories[0]->id,
                            'name' => $categories[0]->name,
                        ],
                        [
                            'id' => $categories[1]->id,
                            'name' => $categories[1]->name,
                        ]
                    ],
                ]
            );
    }
}
