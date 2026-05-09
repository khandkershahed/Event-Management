<?php

namespace App\Services;

use App\Models\Order;

class OrderNumberService
{
    public function generate(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
