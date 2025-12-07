<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'orders';

    public static $order_status = [
        'pending'  => 'Pending',
        'under_processing' => 'Under Processing',
        'cancelled'    => 'Cancelled',
        'complete'    => 'Complete'
    ];

    protected $fillable = [
        'user_id',
        'order_id',
        'first_name',
        'last_name',
        'company_name',
        'country',
        'street_address',
        'apartment',
        'city',
        'state',
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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function status()
    {
        return self::$order_status[$this->status] ?? 'Unknown';
    }

    public function history(){
	    return $this->hasMany('\App\Models\OrderUpdate','order_id');
	}

}
