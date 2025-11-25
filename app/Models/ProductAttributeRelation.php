<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeRelation extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'product_attributes_relations';
    protected $fillable = [
        'json',
        'value',
        'value_attribute_ids',
        'quantity',
        'original_price',
        'price',
        'image',
        'product_id',
        'is_default',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
