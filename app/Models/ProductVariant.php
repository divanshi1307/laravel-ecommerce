<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'variant_name',
        'variant_option',
        'variant_price',
        'variant_images',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class);
    }
}
