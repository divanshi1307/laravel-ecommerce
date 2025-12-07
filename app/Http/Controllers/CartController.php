<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Category;
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
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please login to use add cart.'
                ]);
            }

            return redirect()->route('login')->with('error', 'Please login to use add cart.');
        }

        $rules = [
            'product_id'      => 'required|exists:products,id',
            'variant_id'      => 'nullable',
            'price'           => 'nullable|numeric',
            'original_price'  => 'nullable|numeric',
            'discount'        => 'nullable|numeric',
            'image'           => 'nullable|string',
        ];

        // If request comes from product detail page → require pincode
        if ($request->source === 'product_detail') {
            $rules['pincode'] = 'required|string';
        } else {
            $rules['pincode'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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
            'delivery_country' => $request->country ?? 'India' // default to India
        ]);
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        $cartCount = $cartItems->sum('quantity');
        $cartTotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $totalSaving = $cartItems->sum(fn($item) => ($item->original_price - $item->price) * $item->quantity);
        $cartTotalHtml = view('components.cart-sidebar', compact('cartItems'))->render();

        // Return JSON if AJAX request
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product added to cart!',
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
                'totalSaving' => $totalSaving,
                'cartItem' => $cartItem,
                'cartTotalHtml' => $cartTotalHtml
            ]);
        }

        // Fallback for normal requests
        return redirect()->route('cart.index')->with([
            'status' => 'success',
            'message' => 'Product added to cart!',
        ]);
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

        $subtotal = CartItem::sum(DB::raw('price * quantity'));
        $count = CartItem::count();

        if ($request->ajax()) {
            return response()->json([
                'itemTotal' => number_format($itemTotal),
                'subtotal'  => number_format($subtotal),
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
