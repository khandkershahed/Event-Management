<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeatTypesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $seatTypes = [
            [
                'name' => 'VIP',
                'slug' => Str::slug('VIP'),
                'code' => 'VIP001',
                'image' => null,
                'description' => 'Premium seating with exclusive perks.',
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Economy',
                'slug' => Str::slug('Economy'),
                'code' => 'ECO001',
                'image' => null,
                'description' => 'Affordable seating with standard access.',
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Regular',
                'slug' => Str::slug('Regular'),
                'code' => 'REG001',
                'image' => null,
                'description' => 'Standard seating option.',
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'No Seat',
                'slug' => Str::slug('No Seat'),
                'code' => 'NOS001',
                'image' => null,
                'description' => 'Standing or no physical seat provided.',
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('event_seat_types')->insert($seatTypes);
    }
}
