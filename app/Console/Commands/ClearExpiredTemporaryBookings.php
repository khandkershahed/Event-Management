<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\TemporaryBooking;

class ClearExpiredTemporaryBookings extends Command
{
    protected $signature = 'bookings:clear-expired';

    protected $description = 'Delete expired temporary bookings';

    public function handle()
    {
        $deletedCount = TemporaryBooking::where('reserved_until', '<', now())->delete();
        $this->info("Deleted $deletedCount expired temporary bookings");
    }
}

