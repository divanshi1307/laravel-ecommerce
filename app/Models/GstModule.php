<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstModule extends Model
{
    use HasFactory;
    protected $fillable = ['gst_percentage','slug'];

    // Use slug instead of id for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
