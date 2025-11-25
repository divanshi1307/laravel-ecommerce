<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'photo',
        'banner_link',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function photoUpload()
    {
        return $this->belongsTo(Upload::class, 'photo');
    }

}
