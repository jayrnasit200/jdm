<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Seller User',
            'email' => 'seller@test.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        User::create([
            'name' => 'Owner User',
            'email' => 'owner@test.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
    }
}
