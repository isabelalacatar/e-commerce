<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public $timestamps = true;

    // Define fillable attributes for mass assignment
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    // Relationships: An order item belongs to an order and a product
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
