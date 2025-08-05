<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\EventSeatSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class, // 1st
            RolePermissionSeeder::class, // 2nd
            SettingSeeder::class, // 2nd
            EventTypeSeeder::class,
            EventSeeder::class,
            EventSeatTypesSeeder::class,
            EventSeatSeeder::class,
        ]);
        // $this->call(UserSeeder::class);
    }
}
