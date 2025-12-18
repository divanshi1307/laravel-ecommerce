<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Brand::query();
        if ($request->name) {
            $query->where('brand_name', 'like', '%' . $request->name . '%');
        }
        $brands = $query->orderBy('id', 'desc')->paginate(10);
        return view('brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'slug' => 'required|unique:categories,slug',
            'brand_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        if ($request->hasFile('brand_logo')) {
            $data['brand_logo'] = $request->file('brand_logo')->store('uploads/brand-logo', 'public');
        }
        Brand::create($data);
        return redirect()->route('brands.index')->with('success', 'Brand Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $brand = Brand::findOrFail($id); 
        return view('brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $brands = Brand::findOrFail($id); 
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $brands->id,
            'brand_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('brand_logo')) {
            if ($brands->brand_logo && Storage::disk('public')->exists($brands->brand_logo)) {
                Storage::disk('public')->delete($brands->brand_logo);
            }
            $data['brand_logo'] = $request->file('brand_logo')->store('uploads/brand-logo', 'public');
        }
        $brands->update($data);
        return redirect()->route('brands.index')->with('success', 'Brand Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $brand= Brand::destroy($id); 
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully!');
    }

    public function updateStatus(Request $request)
    {
        $brand = Brand::find($request->id);
        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'Brand not found']);
        }
        $brand->is_active = $request->is_active;
        $brand->save();

        $statusText = $brand->is_active ? 'Active' : 'Inactive';
        return response()->json(['success' => true, 'message' => "Status updated to $statusText"]);
    }
}
