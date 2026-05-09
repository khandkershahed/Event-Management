<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminBaselineRouteTest extends TestCase
{
    public function test_admin_public_login_route_is_stable(): void
    {
        $this->get('/admin/login')->assertSuccessful();
        $this->assertTrue(Route::has('admin.login'));
    }

    public function test_core_admin_route_names_exist(): void
    {
        $routes = [
            'admin.dashboard',
            'admin.event.index',
            'admin.categories.index',
            'admin.event-type.index',
            'admin.venue.index',
            'admin.seating-plans.index',
            'admin.settings.index',
        ];

        foreach ($routes as $routeName) {
            $this->assertTrue(Route::has($routeName), $routeName . ' route is missing.');
        }
    }
}
