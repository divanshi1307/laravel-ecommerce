<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    // ⭐ Store Review & return HTML partial
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($id);
        ProductReview::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id() ?? 0,
            'uname'      => auth()->check() ? auth()->user()->name : $request->uname,
            'uemail'     => auth()->check() ? auth()->user()->email : $request->uemail,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'status'     => 1,
        ]);

        $reviews = ProductReview::where('product_id', $product->id)->where('status', 1)->with('user')->orderBy('id', 'DESC')->get();
        $reviewsHtml = view('landing.partials.reviews', compact('reviews'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully!',
            'reviews_html' => $reviewsHtml,
        ]);
    }
}
