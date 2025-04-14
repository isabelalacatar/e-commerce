<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Product 1',
                'description' => 'Description for Product 1',
                'price' => 19.99,
                'stock_quantity' => 10,
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for Product 2',
                'price' => 29.99,
                'stock_quantity' => 5,
            ],
            [
                'name' => 'Product 3',
                'description' => 'Description for Product 3',
                'price' => 39.99,
                'stock_quantity' => 8,
            ],
        ]);
    }
}
