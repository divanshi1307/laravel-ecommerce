<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Brand;
use App\Models\ProductAttributeRelation;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        $sliderImages = Slider::with('photoUpload')->get();
        $productsQuery = Product::query();

        if ($request->filled('q')) {
            $productsQuery->where('title', 'like', '%' . $request->q . '%');
        }

        $products = $productsQuery->orderBy('id', 'DESC')->paginate(12);
        $brands = Brand::orderBy('id', 'ASC')->get();
        return view('landing.home', compact('categories','sliderImages','products','brands'));
    }

    public function subcategoryProducts(Request $request, $id)
    {
        $subcategory = Category::with('children')->findOrFail($id);
        $category = Category::with('children')->find($subcategory->parent_id);

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        $brands = Brand::whereHas('products', function ($q) use ($id) {
            $q->where('subcategory_id', $id);
        })->get();

        $productsQuery = Product::where('subcategory_id', $subcategory->id);

        $allProductsForRange = $productsQuery->get();
        $allPrices = [];

        foreach ($allProductsForRange as $p) {

            if ($p->product_type === 'simple' && is_numeric($p->price)) {
                $allPrices[] = (float)$p->price;
            }

            if ($p->product_type === 'variant' && !empty($p->variant_price)) {

                $variantArray = is_array($p->variant_price)
                    ? $p->variant_price
                    : json_decode($p->variant_price, true);

                if (is_array($variantArray)) {
                    foreach ($variantArray as $pr) {
                        if (is_numeric($pr)) {
                            $allPrices[] = (float)$pr;
                        }
                    }
                }
            }

            if ($p->relationLoaded('variants') || isset($p->variants)) {
                foreach ($p->variants as $v) {
                    if (is_numeric($v->variant_price)) {
                        $allPrices[] = (float)$v->variant_price;
                    }
                }
            }
        }

        $minAvailablePrice = 0;
        $maxAvailablePrice = $allPrices ? ceil(max($allPrices)) : 50000;

        // ---------------------------------------------
        // PRICE FILTER
        // ---------------------------------------------
        if ($request->filled('min_price') && $request->filled('max_price')) {

            $minFilter = min($request->min_price, $request->max_price);
            $maxFilter = max($request->min_price, $request->max_price);

            $productsQuery->where(function ($q) use ($minFilter, $maxFilter) {
                $q->whereBetween('price', [$minFilter, $maxFilter])
                    ->orWhereHas('variants', function ($v) use ($minFilter, $maxFilter) {
                        $v->whereBetween('variant_price', [$minFilter, $maxFilter]);
                    });
            });
        }

        // ---------------------------------------------
        // SORTING
        // ---------------------------------------------
        if ($request->sort == 'latest') {
            $productsQuery->orderBy('id', 'DESC');
        } elseif ($request->sort == 'popular') {
            $productsQuery->orderBy('views', 'DESC');
        } elseif ($request->sort == 'rating') {
            $productsQuery->orderBy('rating', 'DESC');
        } elseif ($request->sort == 'low_high') {
            $productsQuery->orderBy('price', 'DESC'); 
        } elseif ($request->sort == 'high_low') {
            $productsQuery->orderBy('price', 'ASC'); 
        }

        // ---------------------------------------------
        // ⭐ EXTRACT UNIQUE SIZES
        // ---------------------------------------------
        $allSizes = [];
        $selectedSize = $request->size;

        $attributeRows = ProductAttributeRelation::get();

        foreach ($attributeRows as $row) {
            if ($row->json) {
                $json = json_decode($row->json, true);

                if (isset($json['Size'])) {
                    $size = trim($json['Size']);
                    if ($size !== "" && !in_array($size, $allSizes)) {
                        $allSizes[] = $size;
                    }
                }
            }
        }

        sort($allSizes);

        // ---------------------------------------------
        // ⭐ SIZE FILTER
        // ---------------------------------------------
        if ($request->filled('size')) {

            $selectedSize = $request->size;

            $productIds = ProductAttributeRelation::whereJsonContains('json->Size', $selectedSize)
                ->pluck('product_id')
                ->toArray();

            $productsQuery->whereIn('id', $productIds);
        }

        // ---------------------------------------------
        // PAGINATION
        // ---------------------------------------------
        $perPage = (int)$request->input('per_page', 12);
        $products = $productsQuery->paginate($perPage)->appends($request->all());

        // ---------------------------------------------
        // RETURN VIEW
        // ---------------------------------------------
        return view('landing.sub-category', compact(
            'categories',
            'category',
            'subcategory',
            'products',
            'brands',
            'minAvailablePrice',
            'maxAvailablePrice',
            'allSizes',
            'selectedSize'
        ));
    }

}
