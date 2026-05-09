<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class FrontendEventBaselineTest extends TestCase
{
    public function test_public_event_listing_route_is_stable(): void
    {
        $this->assertTrue(Route::has('all.events'));
        $this->get('/events')->assertSuccessful();
    }

    public function test_public_event_detail_route_is_registered(): void
    {
        $this->assertTrue(Route::has('event.details'));
    }
}
