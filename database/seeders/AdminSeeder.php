<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        Admin::updateOrCreate(
            ['email' => 'khandkershahed23@gmail.com'],
            ['name' => 'Shahed', 'password' => Hash::make('password')]
        );
    }
}
