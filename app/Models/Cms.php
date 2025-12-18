<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    protected $table = 'cms';

    public $timestamps = false;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'top',
        'bottom',
        'meta_title',
        'meta_keywords',
        'meta_desc',
        'date_created',
    ];
}
