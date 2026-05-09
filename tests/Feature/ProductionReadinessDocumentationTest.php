<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProductionReadinessDocumentationTest extends TestCase
{
    public function test_production_documentation_files_exist_and_contain_required_topics(): void
    {
        $requiredFiles = [
            'README.md',
            'DEPLOYMENT.md',
            'RELEASE.md',
            'PRODUCTION_CHECKLIST.md',
            'TROUBLESHOOTING.md',
            '.env.example',
        ];

        foreach ($requiredFiles as $file) {
            $this->assertFileExists(base_path($file), $file . ' should exist for production handover.');
        }

        $deployment = file_get_contents(base_path('DEPLOYMENT.md'));
        $checklist = file_get_contents(base_path('PRODUCTION_CHECKLIST.md'));
        $release = file_get_contents(base_path('RELEASE.md'));

        $this->assertStringContainsString('queue:work', $deployment);
        $this->assertStringContainsString('STRIPE_WEBHOOK_SECRET', $deployment);
        $this->assertStringContainsString('storage:link', $deployment);
        $this->assertStringContainsString('backup', strtolower($deployment));
        $this->assertStringContainsString('/sitemap.xml', $checklist);
        $this->assertStringContainsString('/robots.txt', $checklist);
        $this->assertStringContainsString('30-step conversion is complete', $release);
    }

    public function test_env_example_contains_safe_marketplace_production_keys(): void
    {
        $env = file_get_contents(base_path('.env.example'));

        foreach ([
            'APP_ENV=',
            'APP_DEBUG=',
            'APP_URL=',
            'DB_CONNECTION=',
            'QUEUE_CONNECTION=',
            'MAIL_MAILER=',
            'FILESYSTEM_DISK=',
            'STRIPE_KEY=',
            'STRIPE_SECRET=',
            'STRIPE_WEBHOOK_SECRET=',
            'SESSION_SECURE_COOKIE=',
        ] as $key) {
            $this->assertStringContainsString($key, $env);
        }

        $this->assertStringNotContainsString('sk_live_', $env);
        $this->assertStringNotContainsString('sk_test_', $env);
        $this->assertStringNotContainsString('whsec_', $env);
    }

    public function test_release_critical_routes_exist(): void
    {
        foreach ([
            'homepage',
            'all.events',
            'event.details',
            'public.organizers.show',
            'frontend.cart',
            'frontend.checkout',
            'user.dashboard',
            'user.tickets.index',
            'organizer.dashboard',
            'organizer.check-in.index',
            'admin.marketplace-reports.dashboard',
            'admin.support-tickets.index',
            'admin.audit-logs.index',
            'seo.sitemap',
            'seo.robots',
        ] as $routeName) {
            $this->assertTrue(Route::has($routeName), $routeName . ' route should exist for production smoke tests.');
        }
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $blockedTerms = [
            'TemporaryBooking',
            'TemporaryBookingSeat',
            'EventSeatController',
            'EventSeatTypeController',
            'BookingController',
            'ClearExpiredTemporaryBookings',
        ];

        $routeFiles = collect(glob(base_path('routes/*.php')))
            ->map(fn (string $file) => file_get_contents($file))
            ->implode("\n");

        foreach ($blockedTerms as $term) {
            $this->assertStringNotContainsString($term, $routeFiles);
        }
    }
}
