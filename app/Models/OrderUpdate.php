<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderUpdate extends Model
{
    use HasFactory;
    protected $table = 'order_updates';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'content',
        'date_created',
    ];
	
	public function order(){
	    return $this->belongsTo('\App\Models\Order','order_id');
	}
}
