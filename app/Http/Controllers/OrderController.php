<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                        ->latest()
                        ->paginate(10);

        return view('landing.order-list', compact('orders'));
    }
}
