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
                'message' => 'Please login to use wishlist.'
            ], 401);
        }

        $userId = auth()->id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        // REMOVE ITEM
        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => 'removed',
                'html'   => $this->render(),
                'count'  => Wishlist::where('user_id', $userId)->count()
            ]);
        }

        // ADD ITEM
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $product->id
        ]);

        return response()->json([
            'status' => 'added',
            'html'   => $this->render(),
            'count'  => Wishlist::where('user_id', $userId)->count()
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
            'message' => 'Item removed from wishlist.',
            'wishlist_count' => Wishlist::where('user_id', auth()->id())->count()
        ]);
    }

}
