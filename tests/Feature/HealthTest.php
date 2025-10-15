<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthTest extends TestCase
{
    public function test_api_health_returns_ok()
    {
        $response = $this->getJson('/api/test');
        $response->assertStatus(200)->assertJson(['message' => 'API fonctionne !']);
    }
}
