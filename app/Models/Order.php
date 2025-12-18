<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_id',
        'first_name',
        'last_name',
        'company_name',
        'coupon_id',
        'coupon',
        'country',
        'street_address',
        'apartment',
        'city',
        'state',
        'area',
        'pincode',
        'phone',
        'email',
        'ship_to_different',
        'order_notes',
        'payment_method',
        'subtotal',
        'total',
        'status',
    ];

    public static $order_status = [
        'pending'  => 'Pending',
        'under_processing' => 'Under Processing',
        'cancelled'    => 'Cancelled',
        'complete'    => 'Complete'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function status()
    {
        return self::$order_status[$this->status ?? 'pending'] ?? 'Pending';
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function history(){
	    return $this->hasMany('\App\Models\OrderUpdate','order_id');
	}

    public function isGuestOrder(){
	    if($this->user_id=="guest"){
	        return true;
	    }
	    return false;
	}

}
