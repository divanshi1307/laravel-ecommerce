<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug'];

    // Use slug instead of id for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function productAttributes() {
        return $this->hasMany(ProductAttribute::class, 'age_group_id');
    }
}

