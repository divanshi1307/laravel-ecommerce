<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        return view('landing.account-order', compact('orders'));
    }

    public function vieworder($id)
    {
        $order = Order::with('history', 'items.product')->find($id);
        if (!$order) {
            return redirect('/orders')->with('status', 'Order not found!');
        }
        $order->save();

        foreach ($order->items as $item) {
            if ($item->product->product_type === 'simple') {
                $item->final_price = $item->product->price;
            } else {
                $item->final_price = $item->variant_price;
            }
        }

        // Get shipping charge from user's cart if exists
        $shippingCharge = session('shipping_charge') ?? 0;
        $couponId       = session('coupon_id');
        $discountAmount = session('discount') ?? 0;
        return view('landing.account-order-view', [
            'order' => $order,
            'shippingCharge' => $shippingCharge
        ]);
    }
}
