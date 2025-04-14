<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Insert orders for users with IDs 1 and 2 (assuming they exist in the users table)
        DB::table('orders')->insert([
            [
                'user_id' => 1,  // Assuming user with ID 1 exists
                'total_price' => 59.98,  // Total price for the order
                'status' => 'Pending',  // Order status
            ],
            [
                'user_id' => 2,  // Assuming user with ID 2 exists
                'total_price' => 49.99,  // Total price for the order
                'status' => 'Completed',  // Order status
            ],
            [
                'user_id' => 1,  // Assuming user with ID 1 exists
                'total_price' => 29.99,  // Total price for the order
                'status' => 'Processing',  // Order status
            ],
            [
                'user_id' => 3,  // Assuming user with ID 3 exists
                'total_price' => 99.99,  // Total price for the order
                'status' => 'Cancelled',  // Order status
            ],
        ]);
    }
}
