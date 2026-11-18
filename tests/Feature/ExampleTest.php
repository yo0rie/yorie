<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('id="storefront"', false)
            ->assertSee('window.storefrontPage', false);
    }

    public function test_forwarded_https_is_used_for_asset_urls(): void
    {
        $this->withHeader('X-Forwarded-Proto', 'https')
            ->get('/')
            ->assertOk()
            ->assertSee('https://localhost/build/assets/', false);
    }
}
