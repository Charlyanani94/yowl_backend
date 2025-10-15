<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    public function test_create_category_success()
    {
        $payload = [
            'name' => 'New technologies',
            'description' => 'Category for technology projects',
        ];

        $response = $this->postJson('/api/categories', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'New technologies');

        $this->assertDatabaseHas('categories', ['name' => 'New technologies']);
    }

    public function test_create_category_validation_error()
    {
        
        $payload = ['description' => 'no name'];
        $response = $this->postJson('/api/categories', $payload);
        $response->assertStatus(400);
    }
}
