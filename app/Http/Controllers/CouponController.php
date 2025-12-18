<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function addcoupon($id = null)
    {
        if (request()->isMethod('post')) {

            request()->validate([
                'code' => [
                    'required',
                    Rule::unique('coupons', 'code')->ignore($id),
                ],
                'amt' => 'required|numeric',
                'uses_per_user' => 'required|numeric',
                'min_order_total' => 'required|numeric',
            ], [
                'amt.required' => 'Coupon Amount is required !',
                'amt.numeric' => 'Coupon Amount must be numeric !',
                'uses_per_user.required' => 'No.of coupon use per person are required !',
                'uses_per_user.numeric' => 'No.of coupon use per person must be numeric !',
                'min_order_total.required' => 'Minimum order total is required !',
            ]);

            $row = $id ? Coupon::findOrFail($id) : new Coupon;
            $row->code = request()->code;
            $row->description = request()->description;
            $row->type = request()->type;
            $row->amt = request()->amt;
            $row->uses_per_user = request()->uses_per_user;
            $row->min_order_total = request()->min_order_total;
            $row->max_discount = request()->max_discount ?: null;

            $row->status = request()->status ?? 1;
            $row->public = request()->public !== null ? request()->public : 0;

            $row->product_specific = 0;
            $row->product_id = null;   

            $row->save();

            if (!$id) {
                return redirect()->route('coupon.add')->with('status', 'Coupon Created Successfully!');
            } else {
                return redirect()->route('coupon.list')->with('status', 'Coupon Updated Successfully!');
            }
        }

        $data = [];
        $data['id'] = $id;
        $data['data'] = $id ? Coupon::findOrFail($id) : null;

        return view('admin.coupons.add', $data);
    }


    public function coupons()
    {
        $data['results'] = Coupon::where('product_specific', 0)->paginate(50);
        return view('admin.coupons.index', $data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:coupons,id',
        ]);

        $coupon = Coupon::find($request->id);
        if ($coupon) {
            $coupon->delete();
            return redirect()->route('coupon.list')->with('success', 'Coupon deleted successfully!');
        }

        return redirect()->route('coupon.list')->with('error', 'Coupon not found!');
    }

    // Add or edit a product-specific coupon
    public function add_product_specific_coupon($id = null)
    {
        if (request()->isMethod('post')) {
            request()->validate([
                'code' => [
                    'required',
                    Rule::unique('coupons', 'code')->ignore($id)
                ],
                'amt' => 'required|numeric',
                'product_id' => 'required|integer|exists:products,id',
            ], [
                'amt.required' => 'Coupon Amount is required!',
                'amt.numeric' => 'Coupon Amount must be numeric!',
                'product_id.required' => 'Product field is required!',
                'product_id.exists' => 'Selected product does not exist!',
            ]);

            $coupon = $id ? Coupon::findOrFail($id) : new Coupon;

            $coupon->code = request()->code;
            $coupon->description = request()->description ?? '';
            $coupon->type = request()->type ?? 'fixed';
            $coupon->amt = request()->amt;
            $coupon->status = request()->status ?? 0;
            $coupon->public = 0;
            $coupon->product_specific = 1;
            $coupon->product_id = request()->product_id;
            $coupon->save();

            $message = $id ? 'Coupon updated successfully!' : 'Coupon added successfully!';
            return redirect()->route('product.specific.coupon.list')->with('status', $message);
        }

        $couponData = $id ? Coupon::findOrFail($id) : null;
        $products = \App\Models\Product::where('is_active', 1)->get();

        return view('admin.coupons.product_specific.add', [
            'data' => $couponData,
            'id' => $id ?? '',
            'products' => $products,
        ]);
    }

    // List all product-specific coupons
    public function product_specific_coupons()
    {
        $data['results'] = Coupon::where('product_specific', 1)->paginate(50);
        return view('admin.coupons.product_specific.index', $data);
    }

    // Delete a product-specific coupon
    public function ProductSpecificDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:coupons,id',
        ]);

        $coupon = Coupon::findOrFail($request->id);
        $coupon->delete();

        return redirect()->route('product.specific.coupon.list')->with('status', 'Coupon deleted successfully!');
    }
}
