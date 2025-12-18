<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        // Total Orders
        $totalOrders = Order::where('user_id', $userId)->count();

        // Pending Orders 
        $pendingOrders = Order::where('user_id', $userId)->where('status', 'pending')->count();

        // Total Wishlist
        $totalWishlist = Wishlist::where('user_id', $userId)->count();

        return view('landing.account-dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'totalWishlist'
        ));
    }

    // Show Profile Page
    public function profile()
    {
        $user = Auth::user();
        return view('landing.account-profile', compact('user'));
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone_number'      => 'nullable|string|max:20',
            'password'   => 'nullable|min:6|confirmed',
            'profile_image'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        // Update Password only if filled
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        // Upload Profile Image
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile/'), $filename);
            $user->profile_image = $filename;
        }

        $user->save();
        return back()->with('success', 'Profile updated successfully!');
    }

    /// REVIEWS
    public function reviews()
    {
        $userId = auth()->id();
        $orderedItems = OrderItem::with('product')
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();

        // Fetch user reviews
        $reviews = ProductReview::with('product')
            ->where('user_id', $userId)
            ->get();

        return view('landing.account-review', compact('orderedItems', 'reviews'));
    }
}
