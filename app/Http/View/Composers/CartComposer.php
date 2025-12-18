<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\CartItem;

class CartComposer
{
    public function compose(View $view)
    {
        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product.attributeRelations', 'product.gst')
            ->get();

            $cartItemsCount = $cartItems->count();
            $cartProductIds = $cartItems->pluck('product_id')->toArray();
            $cartTotal = 0;
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

        } else {
            $cartItems = CartItem::whereNull('user_id')
            ->with('product.attributeRelations', 'product.gst')
            ->get();
            $cartItemsCount = $cartItems->count();
            //$cartItems = collect();
            // $cartItemsCount = 0;
            $cartTotal = 0;
            $cartProductIds = $cartItems->pluck('product_id')->toArray();
            // $cartProductIds = [];
        }

        $view->with([
            'cartItems' => $cartItems,
            'cartItemsCount' => $cartItemsCount,
            'cartProductIds' => $cartProductIds,
            'cartTotal' => $cartTotal,
        ]);
    }
}
