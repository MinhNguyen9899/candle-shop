<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'employee@candleshop.com'],
            [
                'name' => 'Nhân viên cửa hàng',
                'phone' => '0900000001',
                'password' => Hash::make('Employee@123'),
                'role' => 'employee',
                'status' => 'active',
            ]
        );
    }
}
