<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProjectBootTest extends TestCase
{
    public function test_homepage_returns_successful_response(): void
    {
        $this->get('/')->assertSuccessful();
    }

    public function test_admin_login_page_returns_successful_response(): void
    {
        $this->get('/admin/login')->assertSuccessful();
    }

    public function test_user_login_page_returns_successful_response(): void
    {
        $this->get('/login')->assertSuccessful();
    }

    public function test_user_register_page_returns_successful_response(): void
    {
        $this->get('/register')->assertSuccessful();
    }

    public function test_route_list_command_runs_without_missing_controller_errors(): void
    {
        $exitCode = Artisan::call('route:list');

        $this->assertSame(0, $exitCode);
    }
}
