<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_can_be_created_by_a_customer()
    {
        // Create a user and assign the 'customer' role
        $user = User::factory()->create([
            'role' => 'customer', // Assuming role is a column in the User model
        ]);

        // Log in the user as customer
        $this->actingAs($user);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 100.00,
            'stock_quantity' => 50,
        ]);

        // Assert that the product was created in the database
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 100.00,
            'stock_quantity' => 50,
        ]);
    }

    /** @test */
    public function a_product_can_be_updated_by_a_customer()
    {
        // Create a user and assign the 'customer' role
        $user = User::factory()->create([
            'role' => 'customer', // Assuming role is a column in the User model
        ]);

        // Log in the user as customer
        $this->actingAs($user);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 100.00,
            'stock_quantity' => 50,
        ]);

        // Update the product's price
        $product->update([
            'price' => 150.00,
        ]);

        // Assert that the updated product exists in the database with the new price
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 150.00,
        ]);
    }

    /** @test */
    public function a_product_can_be_deleted_by_a_customer()
    {
        // Create a user and assign the 'customer' role
        $user = User::factory()->create([
            'role' => 'customer', // Assuming role is a column in the User model
        ]);

        // Log in the user as customer
        $this->actingAs($user);

        // Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 100.00,
            'stock_quantity' => 50,
        ]);

        // Delete the product
        $product->delete();

        // Assert that the product no longer exists in the database
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function can_search_products_by_price_range()
    {
        // Create a user and assign the 'customer' role
        $user = User::factory()->create([
            'role' => 'customer', // Assuming role is a column in the User model
        ]);

        // Log in the user as customer
        $this->actingAs($user);

        // Create products with different prices
        $product1 = Product::create([
            'name' => 'Product 1',
            'price' => 50.00,
            'stock_quantity' => 100,
        ]);

        $product2 = Product::create([
            'name' => 'Product 2',
            'price' => 150.00,
            'stock_quantity' => 100,
        ]);

        // Simulate a search query to find products between 60 and 200
        $products = Product::whereBetween('price', [60, 200])->get();

        // Assert that the second product is in the result, but the first one is not
        $this->assertTrue($products->contains($product2));
        $this->assertFalse($products->contains($product1));
    }
}
