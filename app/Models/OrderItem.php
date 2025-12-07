<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'original_price',
        'discount',
    ];

    // Relationship: Each Item belongs to one Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship: Each Item belongs to one Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship: Connect to Variant if exists
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
