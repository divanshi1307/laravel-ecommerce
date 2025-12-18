<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Product extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'products';
    protected $fillable = [
        'user_id','category_id','subcategory_id','brand_id','gst_id','title','slug','product_item_code','product_type','description',
        'specifications','images','bottom_images','tags','manufacture_date','price','special_price','is_active','highlights','sort_no',
        'stock_quantity','stock_status','meta_title','meta_description','seo_image','meta_tags','meta_snippet'
    ];

    protected $casts = [
        'specifications' => 'array',
        'manufacture_date' => 'date',
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
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function gst()
    {
        return $this->belongsTo(GstModule::class, 'gst_id');
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

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function reviewCount()
    {
        return $this->reviews()->count();
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

    public function getBottomImagesListAttribute()
    {
        if (!$this->bottom_images) return collect();

        $ids = explode(',', $this->bottom_images);
        return Upload::whereIn('id', $ids)->get();
    }

    public function getFirstBottomImageUrlAttribute()
    {
        if (!$this->bottom_images) return 'default.jpg';

        $ids = explode(',', $this->bottom_images);
        $image = Upload::find($ids[0]);

        return $image ? $image->file_name : 'default.jpg';
    }

    public function firstImage()
    {
        return $this->belongsTo(Upload::class, 'images', 'id')->select(['id', 'file_name']);
    }
    
	public function description(){
		$column = \App::getLocale().'_description';
		return $this->{$column};
	}
	
	public function addon_name(){
		$column = \App::getLocale().'_addon_name';
		return $this->{$column};
	}

    public function userHasPurchased()
    {
        if (!auth()->check()) {
            return false;
        }

        return OrderItem::where('product_id', $this->id)
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->exists();
    }

    public function getExpiryDateAttribute()
    {
        return $this->manufacture_date
            ? Carbon::parse($this->manufacture_date)->addYears(3)
            : null;
    }

    public function lowestPriceVariant()
    {
        return $this->hasOne(ProductAttributeRelation::class, 'product_id')->whereNull('deleted_at')->orderBy('price', 'asc');
    }

    public function getDisplayImageAttribute()
    {
        // SIMPLE PRODUCT
        if ($this->product_type === 'simple') {
            return $this->first_image_url;
        }
        // VARIANT / ADULT PRODUCT
        if (in_array($this->product_type, ['variant', 'adult'])) {
            if ($this->lowestPriceVariant && $this->lowestPriceVariant->image) {
                $upload = Upload::find($this->lowestPriceVariant->image);
                return $upload?->file_name ?? $this->first_image_url;
            }
        }
        return $this->first_image_url ?? 'default.jpg';
    }

}
