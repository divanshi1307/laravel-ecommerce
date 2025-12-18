<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public $timestamps = false;
	protected $table = 'coupons';
    protected $fillable = [
        'code',
        'description',
        'amt',
        'type',
        'uses_per_user',
        'min_order_total',
        'max_discount',
        'status',
        'public',
        'product_specific',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
