<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendRoutesTest extends TestCase
{
    public function test_home_page_route_is_available(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_about_page_route_is_available(): void
    {
        $response = $this->get('/about-us');

        $response->assertStatus(200);
    }
}
