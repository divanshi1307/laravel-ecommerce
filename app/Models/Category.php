<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Category extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'categories';
    protected $fillable = [
        'parent_id',
        'user_id',
        'category_name',
        'slug',
        'description',
        'sort_order',
        'is_active',
        'banner',
        'subcategory_image_small',
        'subcategory_image_large',
        'show_on_homepage',
        'meta_title',
        'meta_description',
        'seo_image',
        'meta_tags',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('sort_order', 'ASC');;
    }

}
