<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    public function test_api_test_endpoint_returns_json()
    {
        $response = $this->getJson('/api/test');
        $response->assertStatus(200)->assertJson(['message' => 'API fonctionne !']);
    }

    public function test_register_requires_fields_and_creates_user()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'birthday' => '1995-01-01'
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('data.user.email', 'testuser@example.com');

        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }
}
