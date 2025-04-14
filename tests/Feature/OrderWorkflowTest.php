<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_workflow_with_payment_simulation()
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

        // Prepare the order data with correct structure
        $orderData = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        // Make the API request to place an order
        $response = $this->postJson('/api/orders', $orderData);

        // Assert that the response status is 201 (order created)
        $response->assertStatus(201);

        // Check if stock was reduced
        $product->refresh();
        $this->assertEquals(9, $product->stock_quantity);

        // Optionally: Verify that the order exists in the database
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
    }
}
