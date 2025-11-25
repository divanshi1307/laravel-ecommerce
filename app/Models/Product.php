<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Product extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'products';
    protected $fillable = [
        'user_id','category_id','subcategory_id','brand_id','title','product_item_code','product_type','description',
        'specifications','images','price','special_price','is_active','highlights','sort_no',
        'stock_quantity','stock_status','meta_title','meta_description','seo_image','meta_tags'
    ];

    protected $casts = [
        'specifications' => 'array',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(Upload::class, 'id', 'images');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function seoImage()
    {
        return $this->belongsTo(Upload::class, 'seo_image');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function attributeRelations()
    {
        return $this->hasMany(ProductAttributeRelation::class, 'product_id');
    }
    
    public function getImagesListAttribute()
    {
        if (!$this->images) {
            return collect(); 
        }

        $ids = explode(',', $this->images);
        return Upload::whereIn('id', $ids)->get();
    }

    public function getFirstImageUrlAttribute()
    {
        if (!$this->images) return 'default.jpg';

        $ids = explode(',', $this->images);
        $image = Upload::find($ids[0]);

        return $image ? $image->file_name : 'default.jpg';
    }

    public function firstImage()
    {
        return $this->belongsTo(Upload::class, 'images', 'id')
            ->select(['id', 'file_name']);
    }
}
