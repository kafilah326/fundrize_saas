<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::create([
            'name' => 'John Superadmin',
            'email' => 'superadmin@fundrize.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);
    }
}
