<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function an_order_can_be_placed_and_stock_is_updated()
    {
        // Create a user and assign the 'customer' role
        $user = User::factory()->create([
            'role' => 'customer',  // Assuming 'role' field exists on User model
        ]);

        // Log in the user as customer
        $this->actingAs($user);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 100.00,
            'stock_quantity' => 10,
        ]);

        // Prepare the order data
        $orderData = [
            'products' => [
                [
                    'product_id' => $product->id,  // Correctly structured products field
                    'quantity' => 1,
                ],
            ],
        ];

        // Make the API request to place an order
        $response = $this->postJson('/api/orders', $orderData);

        // Assert that the order was created (201 status)
        $response->assertStatus(201);

        // Assert that the stock quantity has been updated
        $product->refresh();
        $this->assertEquals(9, $product->stock_quantity);

        // Check if the order is in the database
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
    }



    /** @test */
    public function cannot_place_order_with_insufficient_stock()
    {
        // Create a user and assign the 'customer' role
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        // Log in the user as customer
        $this->actingAs($user);

        // Create a product with low stock
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 100.00,
            'stock_quantity' => 1,
        ]);

        // Prepare the order data (request more than available stock)
        $orderData = [
            'product_id' => $product->id,
            'quantity' => 2,
        ];

        // Make the API request to place an order
        $response = $this->postJson('/api/orders', $orderData);

        // Assert that the response status is 422 (unprocessable entity)
        $response->assertStatus(422); // Expect validation error or custom logic to handle stock

        // Ensure that the product's stock has not been changed
        $product->refresh();
        $this->assertEquals(1, $product->stock_quantity);
    }
}
