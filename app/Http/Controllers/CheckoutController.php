<?php

namespace App\Http\Controllers;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Location;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        $savedPincode = $cartItems->first()->pincode ?? null;
        $location = null;
        if ($savedPincode) {
            $location = Location::where('pincode', $savedPincode)->first();
        }
        return view('landing.shop-checkout', compact('cartItems','savedPincode','location'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'company_name'  => 'nullable|string|max:255',
            'street_address'=> 'required|string',
            'apartment'     => 'nullable|string',
            'phone'         => 'required',
            'email'         => 'required|email',
            'order_notes'         => 'nullable|string',
            'payment_method' => 'required|in:bank_transfer,cod,paypal',
        ]);

        $userId = Auth::id();
        $cartItems = CartItem::where('user_id', $userId)->get();

        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate subtotal and total
        $subtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
        $total = $subtotal; 

        $order = Order::create([
            'order_id'      => 'ORD-' . time(),
            'user_id'       => $userId,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'company_name'  => $request->company_name,
            'country'       => $request->country,
            'street_address'=> $request->street_address,
            'apartment'     => $request->apartment,
            'city'          => $request->city, 
            'state'         => $request->state, 
            'pincode'       => $request->pincode,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'order_notes'   => $request->order_notes,
            'payment_method' => $request->payment_method,
            'subtotal'       => $subtotal,
            'total'          => $total,
        ]);

        // 👉 Save Order Items
        foreach ($cartItems as $item) {
            $product = $item->product;
            $gstPercentage = 0;

            if ($product->gst) {
                $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
            }

            $basePrice = $item->price;   // original price from cart
            $gstAmount = ($basePrice * $gstPercentage) / 100;
            $finalPrice = $basePrice + $gstAmount;   // price including GST
            OrderItem::create([
                'order_id'       => $order->id,
                'product_id'     => $item->product_id,
                'variant_id'     => $item->order_id,  
                'quantity'       => $item->quantity,
                'price'          => $finalPrice,  
                'original_price' => $item->original_price,
                'discount'       => $item->discount,
            ]);
        }

        $cart = CartItem::where('user_id', $userId)->delete();
        $realCount = CartItem::where('user_id', $userId)->count(); // expected 0
        session()->put('cart_count', $realCount);
        return redirect()->route('order.success', $order->id)->with('success', 'Order placed successfully!');
    }

    public function orderSuccess($id)
    {
        $order = Order::findOrFail($id);
        return view('landing.order-success', compact('order'));
    }
}
