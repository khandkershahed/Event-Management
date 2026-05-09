<?php

namespace App\Console\Commands;

use App\Services\SeatLockService;
use Illuminate\Console\Command;

class ClearExpiredSeatLocks extends Command
{
    protected $signature = 'seat-locks:clear-expired';

    protected $description = 'Delete expired seat locks from the current SeatLock reservation system.';

    public function handle(SeatLockService $seatLockService): int
    {
        $deleted = $seatLockService->clearExpired();

        $this->info("Expired seat locks cleared: {$deleted}");

        return self::SUCCESS;
    }
}
