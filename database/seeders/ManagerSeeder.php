<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'manager@candleshop.com'],
            [
                'name' => 'Quản trị viên',
                'phone' => '0900000000',
                'password' => Hash::make('Manager@123'),
                'role' => 'manager',
                'status' => 'active',
            ]
        );
    }
}
