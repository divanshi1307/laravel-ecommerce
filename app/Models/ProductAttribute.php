<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'product_attributes';
    protected $fillable = [
        'product_id',
        'attribute_name',
        'attribute_value',
        'baby_weight_id',
        'age_group_id',
        'quantity',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
