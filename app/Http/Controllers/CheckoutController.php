<?php

namespace App\Http\Controllers;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Location;
use App\Models\OrderItem;
use App\Models\ProductAttributeRelation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Mail;


class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        $savedPincode = $cartItems->first()->pincode ?? null;
        $location = null;
        $shippingCharge = 0;

        if ($savedPincode) {
            $location = Location::where('pincode', $savedPincode)->first();
            $shippingCharge = $location->shipping_charge ?? 0;
            session(['shipping_charge' => $shippingCharge]);
        }

        // Reset coupon if not applied now
        session()->forget('coupon_applied_now');
        // if (!session()->has('coupon_applied_now')) {
        //     session()->forget(['coupon_id', 'coupon_code', 'discount']);
        // }

        // === COUPON VALIDATION ===
        $couponId = session('coupon_id');
        $couponCode = session('coupon_code');
        $discountAmount = session('discount') ?? 0;

        if ($couponId) {
            $coupon = Coupon::find($couponId);
            // If coupon deleted or disabled -> remove from session
            if (!$coupon || $coupon->status == 0) {
                session()->forget(['coupon_id', 'coupon_code', 'discount']);
                $couponCode = null;
            } else {
                // Check coupon usage limit per user
                $usedCount = Order::where('user_id', $userId)
                                ->where('coupon_id', $couponId)
                                ->count();

                if ($coupon->uses_per_user !== null && $usedCount >= $coupon->uses_per_user) {
                    // Remove from session if limit is over
                    session()->forget(['coupon_id', 'coupon_code', 'discount']);
                    $couponCode = null;
                    $discountAmount = 0;
                }
            }
        }

        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $finalTotal = $subtotal + $shippingCharge - $discountAmount;

        return view('landing.shop-checkout', compact(
            'cartItems',
            'savedPincode',
            'location',
            'shippingCharge',
            'couponId',
            'couponCode',
            'discountAmount',
            'subtotal',
            'finalTotal'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'company_name'  => 'nullable|string|max:255',
            'street_address'=> 'required|string',
            'apartment'     => 'nullable|string',
            'phone' => 'required|digits:10',
            'email' => ['required','regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
            'order_notes'   => 'nullable|string',
            'payment_method' => 'required|in:bank_transfer,cod,paypal',
        ]);

        $userId = Auth::id();
        $cartItems = CartItem::where('user_id', $userId)->get();

        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();
            $subtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);

            // Coupon Values From Session
            $couponId       = session('coupon_id');
            $discountAmount = session('discount') ?? 0;
            $couponCode     = session('coupon_code'); 
            $shippingCharge = session('shipping_charge') ?? 0;

            $total = $subtotal - $discountAmount;
            if ($total < 0) $total = 0;
            // $total = $subtotal; 
            $isGuest = auth()->user()->is_guest ?? 0;
            $orderNumber = 'ORD-' . time();

            if ($isGuest) {
                $orderNumber .= '-GUEST';
            }

            $order = Order::create([
                'order_id'      => $orderNumber,
                'user_id'       => $userId,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'company_name'  => $request->company_name,
                'coupon_id'     => $couponId,
                'coupon'        => $couponCode,
                'country'       => $request->country,
                'street_address'=> $request->street_address,
                'apartment'     => $request->apartment,
                'city'          => $request->city, 
                'state'         => $request->state, 
                'area'          => $request->area, 
                'pincode'       => $request->pincode,
                'phone'         => $request->phone,
                'email'         => $request->email,
                'order_notes'   => $request->order_notes,
                'payment_method' => $request->payment_method,
                'subtotal'       => $subtotal,
                'total' => $subtotal + $shippingCharge - $discountAmount,
                'status'        => 'pending',
            ]);
        
            // 👉 Save Order Items
            foreach ($cartItems as $item) {
                $product = $item->product;

                // ================================
                // SIMPLE PRODUCT STOCK CHECK
                // ================================
                if ($product->product_type === 'simple') {

                    if ($product->stock_quantity < $item->quantity) {
                        throw new \Exception(
                            "Only {$product->stock_quantity} pieces available for {$product->title}"
                        );
                    }
                }

                // ================================
                // VARIANT STOCK CHECK
                // ================================
                
                if ($item->variant_id) {
                    $variant = ProductAttributeRelation::lockForUpdate()
                        ->findOrFail($item->variant_id);

                    if ($variant->quantity < $item->quantity) {
                        throw new \Exception(
                            "Only {$variant->quantity} pieces available for {$variant->value} pack"
                        );
                    }

                    // $variant->decrement('quantity', $item->quantity);
                }

                // ================================
                // DECREMENT STOCK (AFTER VALIDATION)
                // ================================
                if ($product->product_type === 'simple') {
                    $product->decrement('stock_quantity', $item->quantity);
                    if ($product->fresh()->stock_quantity <= 0) {
                        $product->update([
                            'stock_status' => 'out_of_stock'
                        ]);
                    }
                }

                if ($item->variant_id) {
                    $variant->decrement('quantity', $item->quantity);
                }
                
                $gstPercentage = 0;

                if ($product->gst) {
                    $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                }

                $basePrice = $item->price;  

                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_id'     => $item->product_id,
                    'variant_id'     => $item->variant_id,  
                    'quantity'       => $item->quantity,
                    'price'          => $basePrice,  
                    'original_price' => $item->original_price,
                    'discount'       => $item->discount,
                ]);

            }

            $cart = CartItem::where('user_id', $userId)->delete();
            $realCount = CartItem::where('user_id', $userId)->count(); 
            session()->put('cart_count', $realCount);
            // session()->forget(['coupon_id', 'coupon_code', 'discount']);

            /*
            |--------------------------------------------------------------------------
            | SEND ORDER CONFIRMATION MAIL
            |--------------------------------------------------------------------------
            */
            $order->load('items.product.gst');
            try {
                Mail::to($order->email)->send(new OrderPlacedMail($order));
            } catch (\Exception $e) {
                \Log::error('Order mail failed: ' . $e->getMessage());
            }

            DB::commit();
        }catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
        return redirect()->route('order.success', $order->id)->with('success', 'Order placed successfully!');
    }

    public function orderSuccess($id)
    {
        $order = Order::with('history', 'items.product', 'items.variant', 'coupon')->findOrFail($id);

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
        $couponCode = session('coupon_code');
        $discountAmount = session('discount') ?? 0;

        session()->forget(['coupon_id', 'coupon_code', 'discount']);

        return view('landing.order-success', compact('order', 'shippingCharge','couponId','couponCode','discountAmount'));
    }

}
