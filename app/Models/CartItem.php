<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'cart_items';
    protected $fillable = [
        'user_id',
        'session_id',
        'variant_id',
        'product_id',
        'price',
        'original_price',
        'discount',
        'image',
        'quantity',
        'pincode',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

}
