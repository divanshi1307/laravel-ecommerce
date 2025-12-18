<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdultWaist extends Model
{
    use HasFactory;

    protected $fillable = ['waist_size', 'slug'];

    // Slug-based route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function productAttributes() {
        return $this->hasMany(ProductAttribute::class, 'adult_waist_id');
    }
}
