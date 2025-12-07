<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();
        if ($request->name) {
            $query->where('category_name', 'like', '%' . $request->name . '%');
        }
        $categoryList  = $query->orderBy('id', 'desc')->paginate(10);
        return view('categories.index', compact('categoryList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); 
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:categories,id',
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'banner' => 'nullable|image|mimes:jpg|max:2048',
            'subcategory_image_small' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'subcategory_image_large' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'seo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|in:0,1',
            'show_on_homepage' => 'nullable|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_tags' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->parent_id = $request->parent_id;
        $category->user_id = auth()->id();
        $category->description = $request->description;
        $category->sort_order = $request->sort_order ?? 0;
        $category->is_active = $request->is_active;
        $category->show_on_homepage = $request->has('show_on_homepage') ? 1 : 0;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_tags = $request->meta_tags;

        if ($request->hasfile('banner')) {
            $bannerFile = $request->file('banner');
            $bannerPath = $bannerFile->store('uploads/banners', 'public');
            $category->banner = $bannerPath;
        }

        // Small Subcategory Image
        if ($request->hasFile('subcategory_image_small')) {
            $smallFile = $request->file('subcategory_image_small');
            $smallPath = $smallFile->store('uploads/subcategories', 'public');
            $category->subcategory_image_small = $smallPath;
        }

        // Large Subcategory Image
        if ($request->hasFile('subcategory_image_large')) {
            $largeFile = $request->file('subcategory_image_large');
            $largePath = $largeFile->store('uploads/subcategories', 'public');
            $category->subcategory_image_large = $largePath;
        }

        if ($request->hasfile('seo_image')) {
            $seoFile = $request->file('seo_image');
            $seoPath = $seoFile->store('uploads/seo', 'public');
            $category->seo_image = $seoPath;
        }

        $category->save();
        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $category = Category::findOrFail($id); 
        $categories = Category::where('id', '!=', $category->id)->whereNull('parent_id')->get();    
        return view('categories.edit', compact('category', 'categories'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $category = Category::findOrFail($id);
        $validator = Validator::make($request->all(), [
        // $request->validate([
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $category->id,
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'banner' => 'nullable|image|mimes:jpg|max:2048',
            'subcategory_image_small' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'subcategory_image_large' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'seo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|in:0,1',
            'show_on_homepage' => 'nullable|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_tags' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['banner', 'seo_image','subcategory_image_small','subcategory_image_large']);
    
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('uploads/banners', 'public');
        }

        if ($request->hasFile('subcategory_image_small')) {
            $data['subcategory_image_small'] = $request->file('subcategory_image_small')->store('uploads/subcategories', 'public');
        }

        if ($request->hasFile('subcategory_image_large')) {
            $data['subcategory_image_large'] = $request->file('subcategory_image_large')->store('uploads/subcategories', 'public');
        }
        
        if ($request->hasFile('seo_image')) {
            $data['seo_image'] = $request->file('seo_image')->store('uploads/seo', 'public');
        }
        
        $data['is_active'] = $request->is_active;
        $data['show_on_homepage'] = $request->show_on_homepage ?? 0;
        $data['parent_id'] = $request->parent_id;
        $data['user_id'] = auth()->id();

        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category= Category::destroy($id); 
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }

    public function updateStatus(Request $request)
    {
        $category = Category::find($request->id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found']);
        }
        $category->is_active = $request->is_active;
        $category->save();

        $statusText = $category->is_active ? 'Active' : 'Inactive';
        return response()->json(['success' => true, 'message' => "Status updated to $statusText"]);
    }
}
