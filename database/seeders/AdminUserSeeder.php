<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'peran' => 'admin',
                'jenis_kelamin' => 'laki-laki',
            ]
        );
    }
}
