<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\CartItem;
use App\Models\Brand;
use App\Models\BabyWeight;
use App\Models\AgeGroup;
use App\Models\AdultWaist;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeRelation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $productsQuery = Product::with(['lowestPriceVariant']); 

        if ($request->filled('q')) {
            $productsQuery->where('title', 'like', '%' . $request->q . '%');
        }

        $sessionId = session()->getId();

        $cartProductIds = CartItem::where(function ($q) use ($sessionId) {
            if (Auth::check()) {
                $q->where('user_id', Auth::id());
            } else {
                $q->where('session_id', $sessionId);
            }
        })
        ->pluck('product_id')
        ->toArray();
        $products = $productsQuery->orderBy('id', 'DESC')->paginate(15);
        $brands = Brand::orderBy('id', 'ASC')->get();
        return view('landing.home', compact('categories','sliderImages','products','brands','cartProductIds'));
    }

    public function brandProducts(Request $request, $slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $products = Product::with('lowestPriceVariant')
            ->where('brand_id', $brand->id)
            ->orderBy('id', 'DESC')
            ->paginate(12);

        return view('landing.brand-products', compact('brand', 'products'));
    }

    public function subcategoryProducts(Request $request, $slug)
    {
        $subcategory = Category::with('children')->where('slug', $slug)->firstOrFail();
        $category = Category::with('children')->find($subcategory->parent_id);

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        $brands = Brand::whereHas('products', function ($q) use ($subcategory) {
            $q->where('subcategory_id', $subcategory->id);
        })->get();

        // ALL products
        $productsQuery = Product::where('subcategory_id', $subcategory->id);
        $allProductsForRange = $productsQuery->with('attributeRelations', 'variants', 'gst')->get();

        $allPrices = [];

        foreach ($allProductsForRange as $p) {

            // Get GST %
            $gstPercentage = 0;
            if ($p->gst && !empty($p->gst->gst_percentage)) {
                $gstPercentage = (float) str_replace('%', '', $p->gst->gst_percentage);
            }

            /* -----------------------------------
            SIMPLE PRODUCT PRICE WITH GST
            ----------------------------------- */
            if ($p->product_type === 'simple' && is_numeric($p->price)) {
                $priceWithGst = $p->price + ($p->price * $gstPercentage / 100);
                $allPrices[] = (float) $priceWithGst;
            }

            /* -----------------------------------
            VARIANT / ADULT PRODUCT ATTRIBUTE PRICE WITH GST
            ----------------------------------- */
            if (($p->product_type === 'variant' || $p->product_type === 'adult') 
                && $p->attributeRelations->isNotEmpty()) 
            {
                foreach ($p->attributeRelations as $attr) {
                    if (isset($attr->price) && is_numeric($attr->price)) {
                        $priceWithGst = $attr->price + ($attr->price * $gstPercentage / 100);
                        $allPrices[] = (float) $priceWithGst;
                    }
                }
            }

            /* -----------------------------------
            VARIANT TABLE PRICE WITH GST
            ----------------------------------- */
            if ($p->variants && $p->variants->isNotEmpty()) {
                foreach ($p->variants as $v) {
                    if (is_numeric($v->variant_price)) {
                        $priceWithGst = $v->variant_price + ($v->variant_price * $gstPercentage / 100);
                        $allPrices[] = (float) $priceWithGst;
                    }
                }
            }
        }

        // Final Range
        $minAvailablePrice = $allPrices ? floor(min($allPrices)) : 0;
        $maxAvailablePrice = $allPrices ? ceil(max($allPrices)) : 50000;

        // Filter from request
        $minReq = $request->input('min_price');
        $maxReq = $request->input('max_price');


        if ($minReq !== null && $maxReq !== null) {

            $minFilter = min($minReq, $maxReq);
            $maxFilter = max($minReq, $maxReq);

            $productsQuery->where(function ($q) use ($minFilter, $maxFilter) {
                /* -------------------------------
                SIMPLE PRODUCTS WITH GST
                --------------------------------*/
                $q->where(function ($simpleQ) use ($minFilter, $maxFilter) {
                    $simpleQ->where('product_type', 'simple')
                    ->whereHas('gst', function ($gstQ) use ($minFilter, $maxFilter) {
                        $gstQ->whereRaw("
                            (products.price +
                            (products.price * REPLACE(gst_modules.gst_percentage, '%', '') / 100))
                            BETWEEN ? AND ?
                        ", [$minFilter, $maxFilter]);
                    });
                })

                /* -------------------------------
                VARIANT / ADULT WITH GST
                --------------------------------*/
                ->orWhere(function ($varQ) use ($minFilter, $maxFilter) {
                    $varQ->whereIn('product_type', ['variant', 'adult'])
                    ->whereHas('attributeRelations', function ($attrQ) use ($minFilter, $maxFilter) {
                        $attrQ->whereHas('product.gst', function ($gstQ) use ($minFilter, $maxFilter) {
                            $gstQ->whereRaw("
                                (product_attributes_relations.price +
                                (product_attributes_relations.price * REPLACE(gst_modules.gst_percentage, '%', '') / 100))
                                BETWEEN ? AND ?
                            ", [$minFilter, $maxFilter]);
                        });
                    });
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
            $productsQuery->orderBy('price', 'ASC');
        } elseif ($request->sort == 'high_low') {
            $productsQuery->orderBy('price', 'DESC');
        }

        // ----------------------------------------------------
        // ⭐ EXTRACT UNIQUE SIZE VALUES FROM JSON RELATION
        // ----------------------------------------------------
        $query = Product::query();
        $selectedSize = $request->size;
        $allSizes = [];

        $attributes = ProductAttributeRelation::get();

        foreach ($attributes as $row) {
            if ($row->json) {
                $jsonData = json_decode($row->json, true);

                if (isset($jsonData['Size'])) {
                    $sizes = $jsonData['Size'];

                    if (!is_array($sizes)) {
                        $sizes = [$sizes];
                    }

                    foreach ($sizes as $size) {
                        $size = trim((string) $size);

                        if ($size !== '' && !in_array($size, $allSizes)) {
                            $allSizes[] = $size;
                        }
                    }
                }
            }
        }

        sort($allSizes);

        if ($selectedSize) {
            $productIds = ProductAttributeRelation::whereJsonContains('json->Size', $selectedSize)
                ->pluck('product_id');

            $query->whereIn('id', $productIds);
        }

        // ----------------------------------------------------
        // ⭐ SIZE FILTER (Very Important)
        // ----------------------------------------------------
        if ($request->filled('size')) {
            $selectedSize = $request->size;
            $productsQuery->whereHas('attributeRelations', function ($q) use ($selectedSize) {
                $q->whereJsonContains('json->Size', $selectedSize);
            });
        }
    
        $subcategoryProductIds = Product::where('subcategory_id', $subcategory->id)->pluck('id')->toArray();

        // Baby Weights
        $babyWeights = \App\Models\BabyWeight::whereHas('productAttributes', function ($q) use ($subcategoryProductIds) {
            $q->whereIn('product_id', $subcategoryProductIds);
        })->orderBy('id')->get();

        // Age Groups
        $ageGroups = \App\Models\AgeGroup::whereHas('productAttributes', function ($q) use ($subcategoryProductIds) {
            $q->whereIn('product_id', $subcategoryProductIds);
        })->orderBy('id')->get();

        // Adult Waist
        $adultWaists = \App\Models\AdultWaist::whereHas('productAttributes', function ($q) use ($subcategoryProductIds) {
            $q->whereIn('product_id', $subcategoryProductIds);
        })->orderBy('id')->get();

        $activeFilters = [];

        // ---------------------------------------------
        // BABY WEIGHT FILTER TAG
        // ---------------------------------------------

        if ($request->filled('baby_weight')) {
            $selectedWeightIds = (array)$request->baby_weight;
            $productIds = ProductAttribute::whereIn('baby_weight_id', $selectedWeightIds)
                ->whereIn('product_id', $subcategoryProductIds)
                ->pluck('product_id')
                ->toArray();
            $productsQuery->whereIn('id', $productIds);

            $weights = BabyWeight::whereIn('id', $selectedWeightIds)->get();
            foreach ($weights as $w) {
                $activeFilters[] = [
                    'type' => 'baby_weight',
                    'id' => $w->id,
                    'label' => $w->weight_range
                ];
            }
        }

        // ---------------------------------------------
        // AGE GROUP FILTER TAG
        // ---------------------------------------------

        if ($request->filled('age_group')) {
            $selectedAges = (array)$request->age_group;
            $productIds = ProductAttribute::whereIn('age_group_id', $selectedAges)
                ->whereIn('product_id', $subcategoryProductIds)
                ->pluck('product_id')
                ->toArray();
            $productsQuery->whereIn('id', $productIds);

            $ages = AgeGroup::whereIn('id', $selectedAges)->get();
            foreach ($ages as $a) {
                $activeFilters[] = [
                    'type' => 'age_group',
                    'id' => $a->id,
                    'label' => $a->name
                ];
            }
        }

        // ---------------------------------------------
        // ADULT WAIST FILTER TAG
        // ---------------------------------------------

        if ($request->filled('adult_waist')) {
            $selectedWaists = (array)$request->adult_waist;
            $productIds = ProductAttribute::whereIn('adult_waist_id', $selectedWaists)
                ->whereIn('product_id', $subcategoryProductIds)
                ->pluck('product_id')
                ->toArray();
            $productsQuery->whereIn('id', $productIds);

            $waists = AdultWaist::whereIn('id', $selectedWaists)->get();
            foreach ($waists as $w) {
                $activeFilters[] = [
                    'type' => 'adult_waist',
                    'id' => $w->id,
                    'label' => $w->waist_size
                ];
            }
        }

        $activeFilters[] = [
            'type'  => 'subcategory',
            'id'    => $subcategory->id,
            'label' => $subcategory->category_name
        ];

        // ---------------------------------------------
        // PAGINATION
        // ---------------------------------------------
        $perPage = (int) $request->input('per_page', 12);
        $products = $productsQuery->paginate($perPage)->appends($request->all());

        return view('landing.sub-category', compact(
            'categories',
            'category',
            'subcategory',
            'products',
            'brands',
            'minAvailablePrice',
            'maxAvailablePrice',
            'allSizes',
            'selectedSize',
            'activeFilters',
            'babyWeights',
            'ageGroups',
            'adultWaists'
        ));
    }

}
