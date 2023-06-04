<?php

namespace Tests\Feature;

use App\Models\BookableCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookableCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testEmptyCategory()
    {
        $response = $this->get('/api/bookables/categories');
        $response->assertOk()
            ->assertJsonStructure([
                'data'
            ])
            ->assertJsonCount(0, 'data');
    }

    public function testCategory()
    {
        $categories = BookableCategory::factory(2)->create();

        $response = $this->get('/api/bookables/categories');
        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'name']]
            ])
            ->assertJsonCount(2, 'data')
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
