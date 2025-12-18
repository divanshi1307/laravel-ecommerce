<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabyWeight extends Model
{
    use HasFactory;
    protected $fillable = ['weight_range','slug'];

    // Use slug instead of id for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function productAttributes() {
        return $this->hasMany(ProductAttribute::class, 'baby_weight_id');
    }
}
