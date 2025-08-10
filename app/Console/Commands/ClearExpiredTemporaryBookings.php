<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\TemporaryBooking;
use Illuminate\Support\Facades\DB;
use App\Models\TemporaryBookingSeat;

class ClearExpiredTemporaryBookings extends Command
{
    protected $signature = 'bookings:clear-expired';

    protected $description = 'Delete expired temporary bookings';

    // public function handle()
    // {
    //     $deletedCount = TemporaryBooking::where('reserved_until', '<', now())->delete();
    //     $this->info("Deleted $deletedCount expired temporary bookings");
    // }

    public function handle()
    {
        // Get expired bookings
        $expiredBookings = TemporaryBooking::where('reserved_until', '<', now())->get();

        $releasedSeatsCount = 0;
        foreach ($expiredBookings as $booking) {
            // Get associated seat IDs
            $seatIds = $booking->seats->pluck('seat_id')->toArray();

            // Update event_seats status back to 'active'
            DB::table('event_seats')
                ->whereIn('id', $seatIds)
                ->update(['status' => 'active']);

            $releasedSeatsCount += count($seatIds);
            TemporaryBookingSeat::where('temporary_booking_id', $booking->id)->delete();
            $booking->delete();
        }

        $this->info("Released $releasedSeatsCount seats from expired temporary bookings");
    }
}
