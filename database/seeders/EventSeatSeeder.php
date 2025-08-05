<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Event; // Ensure this path matches your project
use App\Models\EventSeatType; // Ensure this path matches your project

class EventSeatsSeeder extends Seeder
{
    public function run()
    {
        $events = \App\Models\Event::all();
        $seatTypes = \App\Models\EventSeatType::all();
        $now = Carbon::now();

        foreach ($events as $event) {
            foreach ($seatTypes as $seatType) {
                for ($i = 1; $i <= 25; $i++) {
                    $name = "{$seatType->name} Seat {$i}";
                    DB::table('event_seats')->insert([
                        'event_id' => $event->id,
                        'seat_type_id' => $seatType->id,
                        'name' => $name,
                        'slug' => Str::slug($name . '-' . $event->id . '-' . $i),
                        'code' => strtoupper($seatType->slug) . '-' . $i,
                        'price' => $this->generatePrice($seatType->name),
                        'row' => chr(64 + intval(ceil($i / 5))), // A, B, C, ...
                        'column' => $i % 5 === 0 ? 5 : $i % 5,   // 1 to 5
                        'description' => "Seat number $i for {$seatType->name}",
                        'status' => 'active',
                        'added_by' => 'system',
                        'updated_by' => 'system',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    private function generatePrice($seatTypeName)
    {
        switch (strtolower($seatTypeName)) {
            case 'vip':
                return '150.00';
            case 'regular':
                return '100.00';
            case 'economy':
                return '70.00';
            case 'no seat':
                return '50.00';
            default:
                return '0.00';
        }
    }
}
