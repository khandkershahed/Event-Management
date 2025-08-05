<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventTypeSeeder extends Seeder
{
    public function run()
    {
        $eventTypes = [
            ['name' => 'Wedding'],
            ['name' => 'Corporate Meeting'],
            ['name' => 'Birthday Party'],
            ['name' => 'Conference'],
            ['name' => 'Concert'],
            ['name' => 'Product Launch'],
            ['name' => 'Seminar'],
            ['name' => 'Exhibition'],
            ['name' => 'Workshop'],
            ['name' => 'Award Ceremony'],
        ];

        $data = [];

        foreach ($eventTypes as $index => $event) {
            $name = $event['name'];
            $slug = Str::slug($name);

            $data[] = [
                'name'          => $name,
                'slug'          => $slug,
                'code'          => 'EVT-' . strtoupper(Str::random(5)),
                'serial'        => $index + 1,
                'logo'          => "logos/" . $slug . ".png",
                'image'         => "images/" . $slug . ".jpg",
                'banner_image'  => "banners/" . $slug . "_banner.jpg",
                'description'   => "This is a detailed description of the {$name}. It includes all information related to managing a {$name} event professionally.",
                'status'        => 'active',
                'added_by'      => 'system',
                'updated_by'    => 'system',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('event_types')->insert($data);
    }
}
