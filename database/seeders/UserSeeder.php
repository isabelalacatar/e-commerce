<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Insert users into the users table
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),  // Hashed password
                'role' => 'admin',  // User with admin role
            ],
            [
                'name' => 'Customer User 1',
                'email' => 'customer1@example.com',
                'password' => Hash::make('password'),  // Hashed password
                'role' => 'customer',  // User with customer role
            ],
            [
                'name' => 'Customer User 2',
                'email' => 'customer2@example.com',
                'password' => Hash::make('password'),  // Hashed password
                'role' => 'customer',  // User with customer role
            ],
            [
                'name' => 'Customer User 3',
                'email' => 'customer3@example.com',
                'password' => Hash::make('password'),  // Hashed password
                'role' => 'customer',  // User with customer role
            ],
        ]);
    }
}
