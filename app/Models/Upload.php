<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'file_original_name',
        'file_name',
        'user_id',
        'file_size',
        'extension',
        'type',
        'alt_tag'
    ];

    public function getFileUrlAttribute()
    {
        return asset('uploads/all/'.$this->file_name);
    }
    
    public function getImagesListAttribute()
    {
        if (!$this->images) {
            return collect();
        }

        $ids = explode(',', $this->images); 
        return Upload::whereIn('id', $ids)->get();
    }

    public function getSeoImagesListAttribute()
    {
        if (!$this->seo_image) {
            return collect(); 
        }

        $seoImage = Upload::find($this->seo_image);
        return $seoImage ? collect([$seoImage]) : collect();
    }

    public function sliderImage()
    {
        return $this->hasOne(Slider::class, 'upload_id');
    }

}
