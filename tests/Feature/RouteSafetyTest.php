<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RouteSafetyTest extends TestCase
{
    public function test_route_files_do_not_reference_deleted_legacy_ticketing_classes(): void
    {
        $blocked = [
            'BookingController',
            'PaymentController',
            'EventSeatController',
            'EventSeatTypeController',
            'TemporaryBooking',
            'TemporaryBookingSeat',
            'EventSeatType',
            'ClearExpiredTemporaryBookings',
        ];

        foreach (glob(base_path('routes/*.php')) as $routeFile) {
            $contents = file_get_contents($routeFile);

            foreach ($blocked as $term) {
                $this->assertStringNotContainsString($term, $contents, basename($routeFile) . ' still references ' . $term);
            }
        }
    }

    public function test_route_registration_does_not_crash(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));
    }
}
