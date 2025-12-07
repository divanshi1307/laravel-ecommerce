<?php

namespace App\Http\Controllers;
use App\Models\Product;     
use App\Models\Category;     
use App\Models\Wishlist; 

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $wishlist = Wishlist::where('user_id', auth()->id())->with('product.variants')->get();
        return view('landing.shop-wishlist', compact('wishlist','categories'));
    }

    // Add product & return updated list for ajax
    public function toggle(Product $product)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login to add items to wishlist.'
            ], 401);
        }
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            return response()->json([
                'status' => 'already'
            ]);
        }

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);
            $status = 'added';
        }

        return response()->json([
            'html' => $this->render(),
            'status' => $status,
            'count' => Wishlist::where('user_id', auth()->id())->count()
        ]);
    }

    public function render()
    {
        if (!auth()->check()) {
            $wishlist = collect(); 
        } else {
            $wishlist = Wishlist::where('user_id', auth()->id())
                ->with('product')->get();
        }

        return view('components.wishlist-list', compact('wishlist'))->render();
    }


    public function remove(Product $product)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login to manage wishlist.'
            ], 401);
        }

        Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->delete();

        return response()->json([
            'status' => 'removed',
            'message' => 'Item removed from wishlist.'
        ]);
    }

}
