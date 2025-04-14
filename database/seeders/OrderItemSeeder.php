<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert sample data into the order_items table
        DB::table('order_items')->insert([
            [
                'order_id' => 1,  // Assuming order with ID 1 exists
                'product_id' => 1,  // Assuming product with ID 1 exists
                'quantity' => 2,
                'price' => 19.99,  // Price of the product
            ],
            [
                'order_id' => 1,  // Assuming order with ID 1 exists
                'product_id' => 2,  // Assuming product with ID 2 exists
                'quantity' => 1,
                'price' => 29.99,  // Price of the product
            ],
            [
                'order_id' => 2,  // Assuming order with ID 2 exists
                'product_id' => 3,  // Assuming product with ID 3 exists
                'quantity' => 3,
                'price' => 39.99,  // Price of the product
            ],
            [
                'order_id' => 3,  // Assuming order with ID 3 exists
                'product_id' => 1,  // Assuming product with ID 1 exists
                'quantity' => 1,
                'price' => 19.99,  // Price of the product
            ],
        ]);
    }
}
