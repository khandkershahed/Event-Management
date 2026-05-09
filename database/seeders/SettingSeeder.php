<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['id' => 1], [
            'website_name' => 'Event Tailor',
            'site_url' => 'https://www.eventstailor.org',
            'meta_description' => 'A multi-vendor event marketplace platform.',
            'site_favicon' => null,
            'logo' => null,
            'email' => 'admin@eventstailor.org',
            'phone' => '+8801700000000',
            'address' => 'Dhaka, Bangladesh',
        ]);
    }
}
