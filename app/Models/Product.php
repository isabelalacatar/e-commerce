<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define fillable attributes for mass assignment
    protected $fillable = ['name', 'description', 'price', 'stock_quantity'];

    // Relationship: A product can have many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Method to reduce stock quantity when an order is placed
    public function reduceStock($quantity)
    {
        if ($this->stock_quantity >= $quantity) {
            $this->stock_quantity -= $quantity;
            $this->save();
        } else {
            throw new \Exception('Not enough stock');
        }
    }
}
