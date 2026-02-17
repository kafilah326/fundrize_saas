<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Yayasan',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // User
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@email.com',
            'phone' => '+62 812-3456-7890',
            'role' => 'user',
            'avatar' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-1.jpg',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
