<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Show cart page
    public function index()
    {
        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();
        $cartProductIds = $cartItems->pluck('product_id')->toArray();
        return view('landing.shop-cart', compact('cartItems', 'cartProductIds'));
    }

    // Add product to cart

    public function addToCart(Request $request)
    {
        // if (!Auth::check()) {
        //     if ($request->ajax()) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'Please login to use add cart.'
        //         ]);
        //     }
        //     return redirect()->route('login')->with('error', 'Please login to use add cart.');
        // }

        $rules = [
            'product_id'      => 'required|exists:products,id',
            'variant_id'      => 'nullable',
            'price'           => 'nullable|numeric',
            'original_price'  => 'nullable|numeric',
            'discount'        => 'nullable|numeric',
            'image'           => 'nullable|string',
            'pincode'         => $request->source === 'product_detail' ? 'required|string' : 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $location = Location::where('pincode', $request->pincode)->first();
        // $location = Location::where('pincode', $request->pincode != '' ? $request->pincode : '226010')->first();
        
        if ($location) {
            session([
                'shipping_charge' => $location->shipping_charge,
                'delivery_pincode' => $location->pincode,
                'delivery_city'    => $location->city,
                'delivery_state'   => $location->state,
                'delivery_country' => $location->country,
            ]);
        } else {
            // If pincode invalid
            return redirect()->back()->withErrors(['pincode' => 'Delivery not available for this pincode.'])->withInput();
        }

        $userId = Auth::id();

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'price'          => $request->price,
                'original_price' => $request->original_price,
                'discount'       => $request->discount,
                'image'          => $request->image,
                'pincode'        => $request->pincode,
            ]);

            $cartItem->increment('quantity');

        } else {
            $cartItem = CartItem::create([
                'user_id'        => $userId,
                'session_id'     => $request->session()->getId(),
                'product_id'     => $request->product_id,
                'variant_id'     => $request->variant_id,
                'price'          => $request->price,
                'original_price' => $request->original_price,
                'discount'       => $request->discount,
                'image'          => $request->image,
                'pincode'        => $request->pincode,
                'quantity'       => 1,
            ]);
        }

        session([
            'delivery_pincode' => $request->pincode,
            'delivery_city'    => $request->city ?? null,
            'delivery_state'   => $request->state ?? null,
            'delivery_country' => $request->country ?? 'India',
        ]);

        if(empty($userId)){
            $cartItems = CartItem::with('product')->whereNull('user_id')->get();
        }else{
            $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        }

        $cartCount = $cartItems->count();
        $cartTotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $totalSaving = $cartItems->sum(fn($item) =>
            ($item->original_price - $item->price) * $item->quantity
        );

        $cartTotalHtml = view('components.cart-sidebar', compact('cartItems'))->render();
        
        if ($request->ajax()) {
            return response()->json([
                'status'      => 'success',
                'message'     => 'Product added to cart!',
                'product_id'=> $request->product_id,
                'cartItems'   => $cartItems,
                'cartCount'   => $cartCount,     
                'cartTotal'   => $cartTotal,
                'totalSaving' => $totalSaving,
                'cartItem'    => $cartItem,
                'cartTotalHtml' => $cartTotalHtml
            ]);
        }

        return redirect()->route('cart.index')->with([
            'status'  => 'success',
            'message' => 'Product added to cart!',
            'product_id'=> $request->product_id,
        ]);
    }

    //// APPLY COUPON

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if (!$coupon) {
            return back()->with('error', 'Invalid Coupon Code.');
        }

        /*=========================
            CART ITEMS (USER / GUEST)
        ========================== */

        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product.attributeRelations', 'product.gst')
            ->get();
        }else{
            $cartItems = CartItem::whereNull('user_id')
                ->with('product.attributeRelations', 'product.gst')
                ->get();
        }
        
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        /* =========================
            USER COUPON USAGE CHECK
        ========================== */
        /* USER / GUEST COUPON USAGE CHECK */
        if ($coupon->uses_per_user !== null) {
            $usedCount = Order::where('coupon_id', $coupon->id)->count();
            if ($usedCount >= $coupon->uses_per_user) {
                return back()->with('error', 'You have reached the maximum usage limit for this coupon.');
            }
        }
        /* =========================
            CART TOTAL
        ========================== */
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        if ($coupon->min_order_total && $cartTotal < $coupon->min_order_total) {
            return back()->with(
                'error',
                'Order total must be at least ₹' . $coupon->min_order_total
            );
        }

        /* =========================
            DISCOUNT CALCULATION
        ========================== */
        $discount = $coupon->type === 'fixed'
            ? $coupon->amt
            : ($cartTotal * $coupon->amt) / 100;

        /* =========================
            SAVE TO SESSION
        ========================== */
        session([
            'coupon_id'   => $coupon->id,
            'coupon_code' => $coupon->code,
            'discount'    => round($discount, 2),
        ]);
        return back()->with('success', 'Coupon Applied Successfully.');
    }

    // Update quantity
    public function update(Request $request, $id)
    {
        $cart = CartItem::find($id);
        if (!$cart) {
            return response()->json(false);
        }
        
        $cart->quantity = $request->quantity;
        $cart->save();

        $itemTotal = $cart->price * $cart->quantity;
        $subtotal = CartItem::where('user_id', auth()->id())
            ->sum(DB::raw('price * quantity'));

        $grandTotal = $subtotal;
        // $count = CartItem::count();
        $count = CartItem::where('user_id', auth()->id())->sum('quantity');

        if ($request->ajax()) {
            return response()->json([
                'status'    => true,
                'itemTotal' => number_format($itemTotal),
                'subtotal'  => number_format($subtotal),
                'grandTotal'  => number_format($grandTotal),
                'count'     => $count,
            ]);
        }
        return back()->with('success', 'Cart updated!');
    }

    // Remove Cart item
    public function remove(Request $request, $id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['status' => 'error']);
        }

        $productId = $cartItem->product_id;
        $cartItem->delete();

        $count = CartItem::where('user_id', Auth::id())->count();

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        $subtotal = $cartItems->sum(function($item){
            return $item->price * $item->quantity;
        });

        if ($request->ajax()) {
            return response()->json([
                'status' => 'removed',
                'count' => $count,
                'subtotal' => $subtotal,
                'product_id' => $productId,
            ]);
        }
        return back()->with('success', 'Cart Removed!');
    }
}
