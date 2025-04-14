<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Define fillable attributes for mass assignment
    protected $fillable = ['user_id', 'total_price', 'status'];

    // Relationships: An order belongs to a user and has many order items
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Method to calculate total price based on the order items
    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->price * $item->quantity;
        }

        $this->total_price = $total;
        $this->save();
    }

    // Method to check and reduce stock for the products in the order
    public function reduceStockForOrder()
    {
        foreach ($this->items as $item) {
            $product = $item->product;
            $product->reduceStock($item->quantity);
        }
    }
}
