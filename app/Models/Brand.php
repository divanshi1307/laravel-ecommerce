<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'brands';
    protected $fillable = [
        'brand_name',
        'brand_logo',
        'sort_order',
        'is_active',
        'show_on_homepage',
        'description',
        'meta_title',
        'meta_description',
        'meta_tags',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
