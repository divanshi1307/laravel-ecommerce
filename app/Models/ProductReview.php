<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReview extends Model
{
    use HasFactory;

    protected $table = 'product_reviews';

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'status',
    ];

     public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: Review belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
