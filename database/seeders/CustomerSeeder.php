<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'customer@candleshop.com'],
            [
                'name' => 'Khách hàng kiểm thử',
                'phone' => '0900000002',
                'password' => Hash::make('Customer@123'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );
    }
}
