<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttribute; 
use App\Models\ProductAttributeRelation; 
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);
        $categories = Category::whereNull('parent_id')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); 
        $subcategories = Category::whereNotNull('parent_id')->get();
        $brands = Brand::all();
        return view('products.create', compact('categories','brands','subcategories'));
    }

    public function getSubCategories(Request $request)
    {
        $subcategories = Category::where('parent_id', $request->category_id)->get();
        return response()->json($subcategories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'product_type' => 'required',
            'price'              => 'required_if:product_type,simple|numeric|nullable',
            'images' => 'required|array', 
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'seo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'attribute_name.*'  => 'required_if:product_type,variant|max:255',
            'attribute_value.*' => 'required_if:product_type,variant|max:255',
            'stock_quantity' => 'required_if:product_type,simple|numeric|nullable',
        ], [
            'attribute_name.*.required_if'   => 'Please enter the attribute name',
            'attribute_value.*.required_if'  => 'Please enter at least one attribute value ',
            'stock_quantity.required_if' => 'Stock quantity is required for simple products.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => $validator->errors()
        //     ], 422);
        // }

        $product = Product::create([
            'user_id'            => auth()->id(),
            'category_id'        => $request->category_id,
            'subcategory_id'     => $request->subcategory_id,
            'brand_id'           => $request->brand_id,
            'title'              => $request->title,
            'product_item_code'  => $request->product_item_code,
            'product_type'       => $request->product_type,
            'description'        => $request->description, 
            'highlights'         => $request->highlights,
            'sort_no'            => $request->sort_no,
            'is_active'          => $request->is_active ?? 1,  
            'meta_title'         => $request->meta_title,
            'meta_description'   => $request->meta_description,
            'meta_tags'          => $request->meta_tags,
            'specifications'     => json_encode($request->specifications),
        ]);

        if ($request->product_type === 'simple') {
            $qty = (int) $request->stock_quantity;

            $product->update([
                'price'          => $request->price,
                'special_price'  => $request->special_price,
                'stock_quantity' => $qty,
                'stock_status'   => $qty <= 0 ? 'out_of_stock' : ($request->stock_status ?? 'in_stock')
            ]);
        } else {
            $product->update([
                'price'          => null,
                'special_price'  => null,
                'stock_quantity' => null,
                'stock_status'   => null,
            ]);
        }
        // --------------------- MULTIPLE PRODUCT IMAGES ---------------------

        $image_ids = [];
        if($request->hasFile('images')){
            foreach($request->images as $image){
                $originalName = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $fileName = time().rand(100,999).".".$extension;
                $fileSize = $image->getSize();
                $image->move(public_path('uploads/products'), $fileName);

                $upload = Upload::create([
                    'file_original_name' => $originalName,
                    'file_name' => $fileName,
                    'user_id' => auth()->id(),
                    'file_size' => $fileSize,
                    'extension' => $extension,
                    'type' => 'product',
                    'alt_tag' => $request->title,
                ]);

                $image_ids[] = $upload->id;
            }
        }

        // --------------------- SEO IMAGE UPLOAD ---------------------
        $seo_image_id = null;

        if ($request->hasFile('seo_image')) {
            $seo = $request->file('seo_image'); 
            $originalName = $seo->getClientOriginalName();
            $extension = $seo->getClientOriginalExtension();
            $fileName = time().rand(100,999).".".$extension;
            $fileSize = $seo->getSize();
            $seo->move(public_path('uploads/seo'), $fileName);

            $seoUpload = Upload::create([
                'file_original_name' => $originalName,
                'file_name' => $fileName,
                'user_id' => auth()->id(),
                'file_size' => $fileSize,
                'extension' => $extension,
                'type' => 'seo',
                'alt_tag' => $request->title,
            ]);

            $seo_image_id = $seoUpload->id;
        }

        $product->update([
            'images' => implode(',', $image_ids),
            'seo_image' => $seo_image_id,
        ]);

        // --------------------- SAVE PRODUCT ATTRIBUTES ---------------------
    
        if ($request->has('attribute_name') && $request->has('attribute_value')) {

            ProductAttribute::where('product_id', $product->id)->delete();
            $attributes = [];

            foreach ($request->attribute_name as $i => $name) {
                if (!$name) continue;
                $values = $request->attribute_value[$i];
                $values = is_array($values)
                    ? array_map('trim', $values)
                    : array_map('trim', explode(',', $values));

                ProductAttribute::create([
                    'product_id'      => $product->id,
                    'attribute_name'  => $name,
                    'attribute_value' => implode(',', $values)
                ]);

                $attributes[$name] = $values;
            }

            if ($product->product_type === 'variant') {
                $product->attributeRelations()->delete();

                $result = [[]];

                foreach ($attributes as $name => $vals) {
                    $result = collect($result)->flatMap(function ($r) use ($vals, $name) {
                        return collect($vals)->map(fn($v) => $r + [$name => $v]);
                    })->toArray();
                }

                foreach ($result as $row) {
                    $value = implode(' - ', $row); 
                    $ids = ''; 
                    $json = json_encode($row);

                    $product->attributeRelations()->create([
                        'json' => $json,
                        'value' => $value,
                        'value_attribute_ids' => $ids,
                        'price' => 0,
                        'image' => null,
                        'product_id' => $product->id,
                        'is_default' => 0
                    ]);
                }
            }
        }

        // ---------------- SAVE VARIANTS ----------------
        if ($request->product_type == 'variant') {
            $variantName = $request->variant_name;

            foreach ($request->variant_option as $index => $optionName) {
                $optionPrice = $request->variant_price[$index] ?? null;

                $variant = ProductVariant::create([
                    'product_id'     => $product->id,
                    'variant_name'   => $variantName,
                    'variant_option' => $optionName,
                    'variant_price'  => $optionPrice,
                ]);

                if ($request->hasFile("variant_images.$index")) {
                    $imageIds = [];

                    foreach ($request->file("variant_images.$index") as $file) {
                        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $fileSize = $file->getSize();
                        $file->move(public_path('uploads/variant_images'), $fileName);

                        $upload = Upload::create([
                            'file_original_name' => $file->getClientOriginalName(),
                            'file_name'          => $fileName,
                            'user_id'            => auth()->id(),
                            'file_size'          => $fileSize,
                            'extension'          => $file->getClientOriginalExtension(),
                            'type'               => 'variant',
                            'alt_tag'            => $request->title,
                        ]);

                        $imageIds[] = $upload->id;
                    }

                    $variant->update([
                        'variant_images' => implode(',', $imageIds)
                    ]);
                }
            }
        }
        return redirect()->route('products.index')->with('success','Product Added Successfully');
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        $product = Product::with(['variants', 'attributes','attributeRelations'])->findOrFail($id);
        foreach ($product->variants as $variant) {
            if (!empty($variant->variant_images)) {
                $imageIds = is_array($variant->variant_images) 
                            ? $variant->variant_images 
                            : explode(',', $variant->variant_images);

                $variant->images = Upload::whereIn('id', $imageIds)->get();

            } else {
                $variant->images = collect();
            }
        }
        $specifications = is_array($product->specifications)
            ? $product->specifications
            : json_decode($product->specifications, true);

        if (!is_array($specifications)) {
            $specifications = [];
        }
        $categories = Category::where('parent_id', null)->get();
        $subcategories = Category::where('parent_id', $product->category_id)->get();
        $brands = Brand::all();

        return view('products.edit', compact('product', 'categories', 'subcategories', 'brands','specifications'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'price' => 'required_if:product_type,simple|nullable',
            'special_price' => 'nullable|numeric',
            'images'        => ($product->images) ? 'nullable|array' : 'required|array', 
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'seo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'attribute_name.*'  => 'required_if:product_type,variant|max:255',
            'attribute_value.*' => 'required_if:product_type,variant|max:255',
            'stock_quantity' => 'required_if:product_type,simple|numeric|nullable',
        ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => $validator->errors()
        //     ], 422);
        // }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $specifications = [];
        if (!empty($request->specifications)) {
            foreach ($request->specifications as $spec) {
                $label = trim($spec['label'] ?? '');
                $value = trim($spec['value'] ?? '');

                if ($label !== '' && $value !== '') {
                    $specifications[] = [
                        'label' => $label,
                        'value' => $value,
                    ];
                }
            }
        }

        $product->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'brand_id' => $request->brand_id,
            'title' => $request->title,
            'product_type' => $request->product_type,
            'product_item_code'  => $request->product_item_code,
            'description' => $request->description,
            'highlights' => $request->highlights,
            'sort_no' => $request->sort_no ?? 0,
            'is_active' => $request->is_active ?? 1,
            'specifications' => $specifications,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_tags' => $request->meta_tags,
        ]);

         /*
        |--------------------------------------------------------------------------
        | SIMPLE PRODUCT PRICE AND STOCK
        |--------------------------------------------------------------------------
        */
        if ($request->product_type === 'simple') {
            $qty = (int) $request->stock_quantity;

            $product->update([
                'price'          => $request->price,
                'special_price'  => $request->special_price,
                'stock_quantity' => $qty,
                'stock_status'   => $qty <= 0 ? 'out_of_stock' : ($request->stock_status ?? 'in_stock')
            ]);
        } else {
            $product->update([
                'stock_quantity' => null,
                'stock_status'   => null
            ]);
        }
        /*
        |--------------------------------------------------------------------------
        | IMAGE UPLOADS
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('images')) {
            $image_ids = [];
            foreach ($request->file('images') as $image) {
                $fileSize = $image->getSize(); 
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                $image->move(public_path('uploads/products'), $fileName);

                $upload = Upload::create([
                    'file_original_name' => $image->getClientOriginalName(),
                    'file_name' => $fileName,
                    'user_id' => auth()->id(),
                    'file_size' => $fileSize, 
                    'extension' => $image->getClientOriginalExtension(),
                    'type' => 'product',
                    'alt_tag' => $request->title,
                ]);
                $image_ids[] = $upload->id;
            }
            $existing_images = $product->images ? explode(',', $product->images) : [];
            $all_images = array_merge($existing_images, $image_ids);
            $product->update(['images' => implode(',', $all_images)]);
        }

        // ---------------- SINGLE SEO IMAGE ----------------
        if ($request->hasFile('seo_image')) {
            $seo = $request->file('seo_image');
            $originalName = $seo->getClientOriginalName();
            $extension = $seo->getClientOriginalExtension();
            $fileSize = $seo->getSize(); 
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $seo->move(public_path('uploads/seo'), $fileName);

            $seoUpload = Upload::create([
                'file_original_name' => $originalName,
                'file_name' => $fileName,
                'user_id' => auth()->id(),
                'file_size' => $fileSize,
                'extension' => $extension,
                'type' => 'seo',
                'alt_tag' => $request->title,
            ]);
            $product->update(['seo_image' => $seoUpload->id]);
        }

        // --------------------- UPDATE PRODUCT ATTRIBUTES ---------------------

        if ($request->has('attribute_name') && $request->has('attribute_value')) {
            ProductAttribute::where('product_id', $product->id)->delete();

            $attributes = [];

            foreach ($request->attribute_name as $i => $name) {
                if (!$name) continue;

                $values = $request->attribute_value[$i];
                $values = is_array($values)
                    ? array_map('trim', $values)
                    : array_map('trim', explode(',', $values));

                ProductAttribute::create([
                    'product_id'      => $product->id,
                    'attribute_name'  => $name,
                    'attribute_value' => implode(',', $values)
                ]);

                $attributes[$name] = $values;
            }

            if ($product->product_type === 'variant') {
                $product->attributeRelations()->delete();

                $result = [[]];
                foreach ($attributes as $name => $vals) {
                    $result = collect($result)->flatMap(function ($r) use ($vals, $name) {
                        return collect($vals)->map(fn($v) => $r + [$name => $v]);
                    })->toArray();
                }

                foreach ($result as $i => $row) {
                    $value = implode(' - ', $row); 
                    $json = json_encode($row);

                    $relation = $product->attributeRelations()->create([
                        'json'                => $json,
                        'value'               => $value,
                        'value_attribute_ids' => '', 
                        'price'               => $request->price[$i] ?? 0,
                        'original_price'      => $request->original_price[$i] ?? null,
                        'quantity'            => $request->quantity[$i] ?? 0,
                        'product_id'          => $product->id,
                        'is_default'          => (isset($request->is_default) && in_array($value, $request->is_default)) ? 1 : 0,
                    ]);

                    // Handle multiple images
                    $imageIds = [];
                    $oldImages = $request->variant_old_images[$i] ?? [];

                    if ($request->hasFile("image.$i")) {
                        $imageIds = $oldImages;
                        foreach ($request->file("image.$i") as $file) {
                            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $fileSize = $file->getSize();
                            $file->move(public_path('uploads/products'), $fileName);

                            $upload = Upload::create([
                                'file_original_name' => $file->getClientOriginalName(),
                                'file_name'          => $fileName,
                                'file_path'          => "uploads/products/$fileName",
                                'user_id'            => auth()->id(),
                                'file_size'          => $fileSize,
                                'extension'          => $file->getClientOriginalExtension(),
                                'type'               => 'variant_combination',
                                'alt_tag'            => $request->title,
                                'model'              => 'ProductAttributeRelation',
                                'model_id'           => $relation->id,
                            ]);

                            $imageIds[] = $upload->id;
                        }

                        $relation->image = implode('|', $imageIds);
                        $relation->save();
                    } elseif (!empty($oldImages)) {
                        $relation->image = implode('|', $oldImages);
                        $relation->save();
                    }
                }
            }
        }

        // ---------------- SAVE VARIANTS ----------------
        if ($request->product_type == 'variant') {
            $variantName = $request->variant_name;
            foreach ($request->variant_option as $index => $optionName) {
                $variantId = $request->variant_id[$index] ?? null; 
                $variantData = [
                    'product_id'     => $product->id,
                    'variant_name'   => $variantName,
                    'variant_option' => $optionName,
                    'variant_price'  => $request->variant_price[$index] ?? null,
                ];

                if ($variantId) {
                    $variant = ProductVariant::find($variantId);
                    $variant->update($variantData);
                } else {
                    $variant = ProductVariant::create($variantData);
                }

                if ($request->hasFile("variant_images.$index")) {
                    $imageIds = [];

                    foreach ($request->file("variant_images.$index") as $file) {
                        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $fileSize = $file->getSize();
                        $file->move(public_path('uploads/variant_images'), $fileName);

                        $upload = Upload::create([
                            'file_original_name' => $file->getClientOriginalName(),
                            'file_name'          => $fileName,
                            'user_id'            => auth()->id(),
                            'file_size'          => $fileSize,
                            'extension'          => $file->getClientOriginalExtension(),
                            'type'               => 'variant',
                            'alt_tag'            => $request->title,
                        ]);

                        $imageIds[] = $upload->id;
                    }

                    $oldImages = $variant->variant_images ? explode(',', $variant->variant_images) : [];
                    $mergedImages = array_merge($oldImages, $imageIds);

                    $variant->update([
                        'variant_images' => implode(',', $mergedImages)
                    ]);
                }
            }

            if ($request->has('delete_variant_ids')) {
                ProductVariant::whereIn('id', $request->delete_variant_ids)->delete();
            }
        }
        return redirect()->route('products.index')->with('success', 'Product Updated Successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if (!empty($product->images)) {
            $imageArray = explode(',', $product->images);

            foreach ($imageArray as $imageName) {
                $imagePath = public_path('uploads/products/'.$imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $product->variants()->delete();
        $product->delete();

        return redirect()->back()->with('success', 'Product Deleted Successfully');
    }

    public function removeImage($id)
    {
        $image = Upload::find($id);
        if ($image) {
            $filePath = public_path('uploads/products/' . $image->file_name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $products = Product::whereRaw('FIND_IN_SET(?, images)', [$id])->get();
            foreach ($products as $product) {
                $imageIds = explode(',', $product->images);
                $imageIds = array_filter($imageIds, fn($imgId) => $imgId != $id);
                $product->images = implode(',', $imageIds);
                $product->save();
            }
            $image->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function deleteVariantImage($id)
    {
        $image = Upload::find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Image not found.']);
        }
        $filePath = public_path('uploads/variant_images/' . $image->file_name);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $variant = ProductVariant::where('variant_images', 'like', "%{$id}%")->first();
        if ($variant) {
            $imageIds = array_filter(explode(',', $variant->variant_images));
            $updatedImageIds = array_filter($imageIds, fn($imgId) => trim($imgId) != $id);
            $variant->variant_images = implode(',', $updatedImageIds);
            $variant->save();
        }
        $image->forceDelete();
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $product = Product::find($request->id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        $product->is_active = $request->is_active;
        $product->save();

        $statusText = $product->is_active ? 'Active' : 'Inactive';
        return response()->json(['success' => true, 'message' => "Status updated to $statusText"]);
    }

    public function removeVariantImage(Request $request)
    {
        $request->validate([
            'row_id' => 'required|integer',
            'image_id' => 'required|integer',
        ]);

        $row = ProductAttributeRelation::findOrFail($request->row_id);
        $ids = $row->image ? explode('|', $row->image) : [];
        $ids = array_filter($ids, fn($id) => $id != $request->image_id);
        $row->image = implode('|', $ids);
        $row->save();

        return response()->json(['success' => true]);
    }

    public function productDetail($id)
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        $product = Product::with(['images', 'subcategory', 'brand'])->findOrFail($id);
        $subcategory = $product->subcategory;
        $category = Category::find($subcategory->parent_id);

        return view('landing.product-detail', compact(
            'product', 'categories', 'subcategory', 'category'
        ));
    }

}

