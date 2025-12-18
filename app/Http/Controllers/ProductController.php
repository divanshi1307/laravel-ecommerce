<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttribute; 
use App\Models\ProductAttributeRelation; 
use App\Models\Upload;
use App\Models\GstModule;
use App\Models\AgeGroup;
use App\Models\BabyWeight;
use App\Models\AdultWaist;
use App\Models\Wishlist;
use App\Models\ProductReview;
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
        $gst = GstModule::all();
        $baby_weight = BabyWeight::all();
        $age_group = AgeGroup::all();
        $adult_waist = AdultWaist::all();
        return view('products.create', compact('categories','brands','subcategories','gst','baby_weight','age_group','adult_waist'));
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
            'slug' => 'required|unique:products,slug',
            'product_type' => 'required',
            'price'              => 'required_if:product_type,simple|numeric|nullable',
            'images' => 'required|array', 
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'bottom_images' => 'nullable|array',
            'bottom_images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'seo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'attribute_name.*'  => 'required_if:product_type,variant|max:255',
            'attribute_value.*' => 'required_if:product_type,variant|max:255',
            'stock_quantity' => 'required_if:product_type,simple|numeric|nullable',
            'manufacture_date' => 'nullable|date',
        ], [
            'attribute_name.*.required_if'   => 'Please enter the attribute name',
            'attribute_value.*.required_if'  => 'Please enter at least one attribute value ',
            'stock_quantity.required_if' => 'Stock quantity is required for simple products.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $prefix = 'DIM-';
        $lastCode = Product::where('product_item_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('product_item_code');

        if (!$lastCode) {
            $newCode = $prefix . '99';
        } else {
            $number = (int) str_replace($prefix, '', $lastCode);
            if ($number === 99) {
                $newCode = $prefix . '9901';
            } else {
                $newCode = $prefix . ($number + 1);
            }
        }

        $product = Product::create([
            'user_id'            => auth()->id(),
            'category_id'        => $request->category_id,
            'subcategory_id'     => $request->subcategory_id,
            'brand_id'           => $request->brand_id,
            'gst_id'             => $request->gst_id,
            'title'              => $request->title,
            'slug'               => $request->slug,   
            'product_item_code'  => $newCode,
            'product_type'       => $request->product_type,
            'description'        => $request->description, 
            'highlights'         => $request->highlights,
            'sort_no'            => $request->sort_no,
            'is_active'          => $request->is_active ?? 1,  
            'meta_title'         => $request->meta_title,
            'meta_description'   => $request->meta_description,
            'meta_tags'          => $request->meta_tags,
            'meta_snippet'       => $request->meta_snippet,
            'specifications'     => json_encode($request->specifications),
            'tags'               => $request->tags,
            'manufacture_date'   => $request->manufacture_date,
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

        // --------------------- MULTIPLE BOTTOM IMAGES ---------------------

        $bottom_image_ids = [];
        if($request->hasFile('bottom_images')){
            foreach($request->bottom_images as $image){
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

                $bottom_image_ids[] = $upload->id;
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
            'bottom_images' => implode(',', $bottom_image_ids),
            'seo_image' => $seo_image_id,
        ]);

        // --------------------- SAVE PRODUCT ATTRIBUTES ---------------------
    
        // Delete old attributes
        ProductAttribute::where('product_id', $product->id)->delete();

        $attributes = []; // Prepare for variant combinations

        if ($request->has('attribute_name') && $request->has('attribute_value')) {

            // -------------------------------------------------
            // SAVE PRODUCT ATTRIBUTES AND BUILD $attributes ARRAY
            // -------------------------------------------------
            foreach ($request->attribute_name as $i => $name) {

                foreach ($request->attribute_value[$i] as $j => $value) {

                    if (empty($value)) continue;

                    ProductAttribute::create([
                        'product_id'      => $product->id,
                        'attribute_name'  => $name,
                        'attribute_value' => $value,
                        'baby_weight_id'  => $request->baby_weight_id[$i][$j] ?? null,
                        'age_group_id'    => $request->age_group_id[$i][$j] ?? null,
                        'adult_waist_id'    => $request->adult_waist_id[$i][$j] ?? null,
                    ]);

                    // Build array for combination generation
                    $attributes[$name][] = $value;
                }
            }

            // -------------------------------------------------
            // CREATE VARIANT COMBINATIONS
            // -------------------------------------------------
            if ($product->product_type === 'variant' || $product->product_type === 'adult' && !empty($attributes)) {

                $product->attributeRelations()->delete();

                // Generate all combinations
                $combinations = [[]];
                foreach ($attributes as $key => $values) {
                    $combinations = collect($combinations)->flatMap(function($combo) use ($key, $values) {
                        return collect($values)->map(fn($v) => array_merge($combo, [$key => $v]));
                    })->toArray();
                }

                // Save each combination
                foreach ($combinations as $i => $combo) {

                    $valueStr = implode(' - ', $combo);

                    $relation = $product->attributeRelations()->create([
                        'json'           => json_encode($combo),
                        'value'          => $valueStr,
                        'price'          => $request->price[$i] ?? 0,
                        'original_price' => $request->original_price[$i] ?? null,
                        'quantity'       => $request->quantity[$i] ?? 0,
                        'is_default'     => 0
                    ]);

                    // Handle variant images
                    $imageIds = $request->variant_old_images[$i] ?? [];

                    if ($request->hasFile("image.$i")) {
                        foreach ($request->file("image.$i") as $file) {
                            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('uploads/products'), $fileName);

                            $upload = Upload::create([
                                'file_original_name' => $file->getClientOriginalName(),
                                'file_name'          => $fileName,
                                'file_path'          => "uploads/products/$fileName",
                                'user_id'            => auth()->id(),
                                'file_size'          => $file->getSize(),
                                'extension'          => $file->getClientOriginalExtension(),
                                'type'               => 'variant_combination',
                                'alt_tag'            => $request->title,
                                'model'              => 'ProductAttributeRelation',
                                'model_id'           => $relation->id,
                            ]);

                            $imageIds[] = $upload->id;
                        }
                    }

                    if (!empty($imageIds)) {
                        $relation->image = implode('|', $imageIds);
                        $relation->save();
                    }
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

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with(['variants', 'attributes','attributeRelations'])->findOrFail($id);
        foreach ($product->variants ?? collect() as $variant) {
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
        $gst = GstModule::all();
        $baby_weight = BabyWeight::all();
        $age_group = AgeGroup::all();
        $adult_waist = AdultWaist::all();

        $attributes = [];

        foreach ($product->attributes as $attr) {
            $attrName = $attr->attribute_name;

            if (!isset($attributes[$attrName])) {
                $attributes[$attrName] = [
                    "attribute_name" => $attrName,
                    "rows" => []
                ];
            }

            $attributes[$attrName]["rows"][] = [
                "baby_weight_id"  => $attr->baby_weight_id,   
                "age_group_id"    => $attr->age_group_id ,
                "adult_waist_id"    => $attr->adult_waist_id ,
                "attribute_value" => $attr->attribute_value
            ];
        }

        $attributes = array_values($attributes);

        return view('products.edit', compact('product', 'categories', 'subcategories', 'brands', 'gst','specifications','attributes','baby_weight','age_group','adult_waist'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'price' => 'required_if:product_type,simple|nullable',
            'special_price' => 'nullable|numeric',
            'images'        => ($product->images) ? 'nullable|array' : 'required|array', 
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'bottom_images' => 'nullable|array',
            'bottom_images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'seo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'attribute_name.*'  => 'required_if:product_type,variant|max:255',
            'attribute_value.*' => 'required_if:product_type,variant|max:255',
            'stock_quantity' => 'required_if:product_type,simple|numeric|nullable',
            'manufacture_date' => 'nullable|date',
        ]);

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
            'gst_id' => $request->gst_id,
            'title' => $request->title,
            'slug' => $request->slug, 
            'product_type' => $request->product_type,
            // 'product_item_code'  => $request->product_item_code,
            'description' => $request->description,
            'highlights' => $request->highlights,
            'sort_no' => $request->sort_no ?? 0,
            'is_active' => $request->is_active ?? 1,
            'specifications' => $specifications,
            'tags' => $request->tags,
            'manufacture_date'   => $request->manufacture_date,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_tags' => $request->meta_tags,
            'meta_snippet' => $request->meta_snippet,
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
        | MULTIPLE IMAGE UPLOADS
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

        /*
        |--------------------------------------------------------------------------
        | MULTIPLE BOTTOM IMAGE 
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('bottom_images')) {
            $bottom_image_ids = [];
            foreach ($request->file('bottom_images') as $image) {
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
                $bottom_image_ids[] = $upload->id;
            }
            $existing_images = $product->bottom_images ? explode(',', $product->bottom_images) : [];
            $all_images = array_merge($existing_images, $bottom_image_ids);
            $product->update(['bottom_images' => implode(',', $all_images)]);
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

        // Delete old attributes
        ProductAttribute::where('product_id', $product->id)->delete();

        $attributes = []; // for combination generation

        if ($request->has('attribute_name')) {

            foreach ($request->attribute_name as $index => $attrName) {
                if (empty($attrName)) continue;

                $rowValues     = $request->attribute_value[$index] ?? [];
                $rowBabyWeight = $request->baby_weight_id[$index] ?? [];
                $rowAgeGroups  = $request->age_group_id[$index] ?? [];
                $rowAdultWaist  = $request->adult_waist_id[$index] ?? [];

                foreach ($rowValues as $rowIndex => $value) {
                    if (empty($value)) continue;

                    $babyWeightId = $rowBabyWeight[$rowIndex] ?? null;
                    $ageGroupId   = $rowAgeGroups[$rowIndex] ?? null;
                    $adultWaistId = $rowAdultWaist[$rowIndex] ?? null;

                    // Save attribute
                    ProductAttribute::create([
                        'product_id'      => $product->id,
                        'attribute_name'  => $attrName,
                        'attribute_value' => $value,
                        'baby_weight_id'  => $babyWeightId,
                        'age_group_id'    => $ageGroupId,
                        'adult_waist_id'  => $adultWaistId,
                    ]);

                    // Save for combination generation
                    $attributes[$attrName][] = [
                        'value'          => $value,
                        'baby_weight_id' => $babyWeightId,
                        'age_group_id'   => $ageGroupId,
                        'adult_waist_id' => $adultWaistId,
                    ];
                }
            }
        }

        // ---------------------------------------------------
        // UPDATE VARIANT COMBINATIONS
        // ---------------------------------------------------
        if ($product->product_type === 'variant' || $product->product_type === 'adult' && !empty($attributes)) {

            // Delete old combinations
            $product->attributeRelations()->delete();

            // Generate all combinations
            $combinations = [[]];
            foreach ($attributes as $attrName => $values) {
                $combinations = collect($combinations)->flatMap(function($combo) use ($attrName, $values) {
                    return collect($values)->map(fn($v) => array_merge($combo, [$attrName => $v]));
                })->toArray();
            }

            // Save each combination
            foreach ($combinations as $i => $combo) {
                $valueStr = implode(' - ', array_map(fn($item) => $item['value'], $combo));

                $relation = $product->attributeRelations()->create([
                    'json'           => json_encode($combo),
                    'value'          => $valueStr,
                    'value_attribute_ids' => '', // optional
                    'price'          => $request->price[$i] ?? 0,
                    'original_price' => $request->original_price[$i] ?? null,
                    'quantity'       => $request->quantity[$i] ?? 0,
                    'is_default'     => (!empty($request->is_default) && in_array($valueStr, $request->is_default)) ? 1 : 0,
                ]);

                // Handle images
                $imageIds = $request->variant_old_images[$i] ?? [];
                if ($request->hasFile("image.$i")) {
                    foreach ($request->file("image.$i") as $file) {
                        if (!$file->isValid()) continue;

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
                            'alt_tag'            => $product->title,
                            'model'              => 'ProductAttributeRelation',
                            'model_id'           => $relation->id,
                        ]);

                        $imageIds[] = $upload->id;
                    }
                }

                if (!empty($imageIds)) {
                    $relation->image = implode('|', $imageIds);
                    $relation->save();
                }
            }
        }

        // ---------------- SAVE VARIANTS ----------------
        if ($request->product_type == 'variant') {
            $variantName = $request->variant_name;

            foreach ($request->variant_option as $index => $optionName) {
                $variantId = $request->variant_id[$index] ?? null;
                $variantPrice = $request->variant_price[$index] ?? null;

                if (empty($optionName) && empty($variantPrice)) {
                    continue;
                }

                $variantData = [
                    'product_id'     => $product->id,
                    'variant_name'   => $variantName,
                    'variant_option' => $optionName,
                    'variant_price'  => $variantPrice,
                ];

                if ($variantId) {
                    $variant = ProductVariant::find($variantId);

                    // Only update if data changed
                    if ($variant && (
                        $variant->variant_name != $variantData['variant_name'] ||
                        $variant->variant_option != $variantData['variant_option'] ||
                        $variant->variant_price != $variantData['variant_price']
                    )) {
                        $variant->update($variantData);
                    }
                } else {
                    // Only create if there is meaningful data
                    if (!empty($variantData['variant_option']) || !empty($variantData['variant_price'])) {
                        $variant = ProductVariant::create($variantData);
                    } else {
                        continue;
                    }
                }

                // Handle images
                if ($request->hasFile("variant_images.$index")) {
                    $imageIds = [];
                    foreach ($request->file("variant_images.$index") as $file) {
                        $fileSize = $file->getSize();
                        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
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
                    $mergedImages = array_unique(array_merge($oldImages, $imageIds));

                    $variant->update([
                        'variant_images' => implode(',', $mergedImages)
                    ]);
                }
            }

            // Delete variants if requested
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

    public function removeBottomImage($id)
    {
        $image = Upload::find($id);
        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Image not found']);
        }
        $filePath = public_path('uploads/products/' . $image->file_name);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $products = Product::whereRaw('FIND_IN_SET(?, bottom_images)', [$id])->get();

        foreach ($products as $product) {
            $imageIds = array_filter(explode(',', $product->bottom_images));
            $imageIds = array_filter($imageIds, function ($imgId) use ($id) {
                return $imgId != $id;
            });
            $product->bottom_images = implode(',', $imageIds);
            $product->save();
        }
        $image->delete();
        return response()->json(['success' => true]);
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

    public function productDetail($slug)
    {
        $product = Product::with(['subcategory', 'brand','attributeRelations','variants'])->where('slug', $slug)->firstOrFail();;
        $subcategory = $product->subcategory;
        $category = $subcategory ? Category::find($subcategory->parent_id) : null;
        // $defaultVariant = $product->variants->sortBy('variant_price')->first();
        $defaultVariant = $product->attributeRelations->sortBy('price')->first();

        $wishlistItems = Wishlist::where('user_id', auth()->id())->pluck('product_id');
        $relatedProducts = Product::where('subcategory_id', $product->subcategory_id)->where('id', '!=', $product->id)->with(['variants', 'images'])->take(10)->get();

        $reviews = ProductReview::where('product_id', $product->id)->where('status', 1)->with('user')->get();
        
        $alreadyReviewed = false;
        if (auth()->check()) {
            $alreadyReviewed = ProductReview::where('product_id', $product->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('landing.product-detail', compact('product', 'subcategory', 'category','relatedProducts','defaultVariant','wishlistItems','reviews','alreadyReviewed'));
    }

    public function getAttributeImage($id)
    {
        $attr = ProductAttributeRelation::findOrFail($id);
        $product = $attr->product; 
        $image = Upload::find($attr->image);

        // GST %
        $gstPercentage = $product->gst ? (float) str_replace('%', '', $product->gst->gst_percentage) : 0;

        // Price with GST
        $priceWithGst = $attr->price + ($attr->price * $gstPercentage / 100);
        $originalPriceWithGst = $attr->original_price + ($attr->original_price * $gstPercentage / 100);

        $discount = 0;
        if ($attr->original_price > 0) {
            $discount = round((($attr->original_price - $attr->price) / $attr->original_price) * 100);
        }

        return response()->json([
            'id' => $attr->id, 
            'price' => $priceWithGst,                     
            'original_price' => $originalPriceWithGst,    
            'discount' => $discount,
            'image' => asset('uploads/products/' . $image->file_name),
            'stock' => $attr->quantity,
        ]);
    }


}

