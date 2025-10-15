<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Laravel\Sanctum\Sanctum;

class PostTest extends TestCase
{
    public function test_create_post_success()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Sanctum::actingAs($user);

        $payload = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'My project Arduino',
            'content' => 'Detailed description of my project',
            'link' => 'https://github.com/user/project',
            'photos' => json_encode(['img1.jpg','img2.jpg']),
        ];

        $response = $this->postJson('/api/posts', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'My project Arduino')
                 ->assertJsonStructure(['status','error','data' => ['id','title','content','user','category'],'message']);

        $this->assertDatabaseHas('posts', ['title' => 'My project Arduino', 'user_id' => $user->id]);
    }

    public function test_create_post_validation_error()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'user_id' => $user->id,
            // missing title/content/category_id
        ];

        $response = $this->postJson('/api/posts', $payload);

        $response->assertStatus(400);
    }
}
