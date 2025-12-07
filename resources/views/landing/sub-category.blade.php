@extends('landing.layout')

@section('content')
<div class="page-wraper">
    <div class="page-content bg-light">
        <!-- Banner -->
        <div class="dz-bnr-inr bg-secondary overlay-black-light" style="background-image:url({{ asset('landing/images/background/bg1.jpg')}});">
            <div class="container">
                <div class="dz-bnr-inr-entry">
                    <h1>{{ $subcategory->category_name }}</h1>

                    <nav aria-label="breadcrumb" class="breadcrumb-row">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"> Home</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ url('/category/'.$category->id) }}">{{ $category->category_name }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $subcategory->category_name }}</li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
        <!-- Banner End -->

        <section class="content-inner-3 pt-3">
            <div class="container-fluid">
                <div class="row mt-xl-2 mt-0">

                    <!-- Sidebar Filters -->
                    <div class="col-20 col-xl-3">
                        <div class="sticky-xl-top">
                            <div class="shop-filter">
                                <aside>
                                    <div class="d-flex align-items-center justify-content-between m-b30">
                                        <h6 class="title mb-0">FILTER</h6>
                                    </div>

                                    <!-- Sidebar Search -->
                                    <div class="widget widget_categories">
                                        <div class="resizable-iframe">
                                            <div class="sidebar" id="sidebar">
                                                <div class="search-container">
                                                    <input type="text" id="searchInput" class="search-input"
                                                        placeholder="Search categories or items...">
                                                </div>

                                                <div id="noResults" class="no-results">No results found</div>

                                                @foreach ($categories as $cat)
                                                    <div class="category">
                                                        <div class="cat-header">
                                                            <span class="cat-title">{{ $cat->category_name }}</span>
                                                        </div>

                                                        <div class="cat-list">
                                                            @foreach ($cat->children as $child)
                                                                <a href="{{ url('subcategory/' . $child->id) }}"
                                                                class="cat-item"
                                                                data-cat="{{ $cat->slug }}"
                                                                data-value="{{ $child->slug }}">
                                                                    <span>{{ $child->category_name }}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price Filter -->
                                    <div class="widget">
                                        <h6 class="widget-title">PRICE</h6>

                                        <form id="priceFilterForm" method="GET" action="{{ url()->current() }}">
                                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">

                                            <div class="price-slide range-slider">
                                                <div class="range-slider style-1">
                                                    <div id="priceSlider" class="mb-3"></div>

                                                    <div class="d-flex justify-content-between">
                                                        <small id="slider-min-label">₹{{ request('min_price', $minAvailablePrice) }}</small>
                                                        <small id="slider-max-label">₹{{ request('max_price', $maxAvailablePrice) }}</small>
                                                    </div>

                                                    <input type="hidden" id="min_price" name="min_price"
                                                        value="{{ request('min_price', $minAvailablePrice) }}">
                                                    <input type="hidden" id="max_price" name="max_price"
                                                        value="{{ request('max_price', $maxAvailablePrice) }}">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Size -->
                                    {{-- <form method="GET">
                                        <div class="widget">
                                            <h6 class="widget-title">Size</h6>
                                            <div class="btn-group product-size">

                                                @foreach($allSizes as $index => $size)
                                                    <input type="radio" class="btn-check" name="size"
                                                        id="size{{ $index }}" value="{{ $size }}" {{ $selectedSize == $size ? 'checked' : '' }}
                                                        onchange="this.form.submit()">

                                                    <label class="btn" for="size{{ $index }}">{{ $size }}</label>
                                                @endforeach

                                            </div>
                                        </div>
                                    </form> --}}

                                    <!-- BABY WEIGHT Filter -->
                                    <div class="widget widget_categories">
                                        <h6 class="widget-title">COMPATIBLE BABY WEIGHT</h6>

                                        @php
                                            $babyWeights = \App\Models\BabyWeight::orderBy('id')->get();
                                            $selectedWeight = request('baby_weight') ?? [];
                                            if(!is_array($selectedWeight)) {
                                                $selectedWeight = [$selectedWeight];
                                            }
                                        @endphp

                                        <form id="babyWeightFilterForm" method="GET" action="{{ url()->current() }}">
                                            @foreach(request()->except('baby_weight') as $key => $value)
                                                @if(is_array($value))
                                                    @foreach($value as $v)
                                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                                    @endforeach
                                                @else
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endif
                                            @endforeach

                                            @php
                                                $babyWeights = \App\Models\BabyWeight::orderBy('id')->get();
                                                $selectedWeights = request('baby_weight', []);
                                                if(!is_array($selectedWeights)) {
                                                    $selectedWeights = [$selectedWeights];
                                                }
                                            @endphp

                                            @if($babyWeights->count() > 0)
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($babyWeights as $bw)
                                                        <li class="cat-item mb-1">
                                                            <label class="d-flex align-items-center">
                                                                <input type="checkbox" name="baby_weight[]" value="{{ $bw->id }}"
                                                                    onchange="this.form.submit()"
                                                                    @if(in_array($bw->id, $selectedWeights)) checked @endif
                                                                    class="me-2">
                                                                <span>{{ $bw->weight_range }}</span>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">No baby weights available</p>
                                            @endif
                                        </form>
                                    </div>

                                    <!-- AGE GROUP Filter -->
                                    <div class="widget widget_categories">
                                        <h6 class="widget-title">AGE GROUP</h6>

                                        @php
                                            $ageGroups = \App\Models\AgeGroup::orderBy('id')->get();

                                            $selectedAges = request('age_group', []);
                                            if (!is_array($selectedAges)) {
                                                $selectedAges = [$selectedAges];
                                            }
                                        @endphp

                                        <form id="ageGroupFilterForm" method="GET" action="{{ url()->current() }}">
                                            @foreach(request()->except('age_group') as $key => $value)
                                                @if(is_array($value))
                                                    @foreach($value as $v)
                                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                                    @endforeach
                                                @else
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endif
                                            @endforeach

                                            @if($ageGroups->count() > 0)
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($ageGroups as $ag)
                                                        <li class="cat-item mb-1">
                                                            <label class="d-flex align-items-center">
                                                                <input type="checkbox" name="age_group[]" value="{{ $ag->id }}"
                                                                    onchange="this.form.submit()"
                                                                    @if(in_array($ag->id, $selectedAges)) checked @endif
                                                                    class="me-2">
                                                                <span>{{ $ag->name }}</span>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">No age groups available</p>
                                            @endif
                                        </form>
                                    </div>

                                    <!-- Brands -->
                                    <div class="widget widget_categories">
                                        <h6 class="widget-title">BROWSE BY BRAND</h6>

                                        @if($brands->count() > 0)
                                            @foreach($brands as $brand)
                                                <ul>
                                                    <li class="cat-item">
                                                        <a href="javascript:void(0)">{{ $brand->brand_name }}</a>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        @else
                                            <p class="text-muted">No brands available</p>
                                        @endif
                                    </div>

                                    <a href="{{ url()->current() }}" class="btn btn-sm btn-secondary">
                                        RESET
                                    </a>
                                </aside>
                            </div>
                        </div>
                    </div>

                    <!-- MAIN CONTENT -->
                    <div class="col-80 col-xl-9">
                        <h4 class="mb-3">{{ $subcategory->category_name }}</h4>

                        <!-- Sub Category -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="swiper category-swiper">
                                    <div class="swiper-wrapper">
                                        @if($category && $category->children->count() > 0)
                                            @foreach ($category->children as $sub)
                                                <div class="swiper-slide">
                                                    <div class="shop-card">
                                                        <div class="dz-media rounded">
                                                            <img src="{{ asset('storage/' . $sub->subcategory_image_large) }}" 
                                                                alt="{{ $sub->category_name }}">
                                                        </div>
                                                        <div class="dz-content">
                                                            <h6 class="title">
                                                                <a href="{{ url('/subcategory/' . $sub->id) }}">
                                                                    {{ $sub->category_name }}
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No subcategories found.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sorting + Per Page -->
                        <div class="filter-wrapper border-top p-t20">
                            {{-- <div class="filter-left-area">								
                                <ul class="filter-tag">
                                    <li>
                                        <a href="javascript:void(0);" class="tag-btn">Dresses 
                                            <i class="icon feather icon-x tag-close"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="tag-btn">Tops
                                            <i class="icon feather icon-x tag-close"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="tag-btn">Outerwear 
                                            <i class="icon feather icon-x tag-close"></i>
                                        </a>
                                    </li>
                                </ul>
                                <span>Showing 1–5 Of 50 Results</span>
                            </div> --}}

                            <!-- Filters -->
                            <div class="filter-right-area">
                                <a href="javascript:void(0);" class="panel-btn">
                                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25" width="20" height="20"><g id="Layer_28" data-name="Layer 28"><path d="M2.54,5H15v.5A1.5,1.5,0,0,0,16.5,7h2A1.5,1.5,0,0,0,20,5.5V5h2.33a.5.5,0,0,0,0-1H20V3.5A1.5,1.5,0,0,0,18.5,2h-2A1.5,1.5,0,0,0,15,3.5V4H2.54a.5.5,0,0,0,0,1ZM16,3.5a.5.5,0,0,1,.5-.5h2a.5.5,0,0,1,.5.5v2a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1-.5-.5Z"></path><path d="M22.4,20H18v-.5A1.5,1.5,0,0,0,16.5,18h-2A1.5,1.5,0,0,0,13,19.5V20H2.55a.5.5,0,0,0,0,1H13v.5A1.5,1.5,0,0,0,14.5,23h2A1.5,1.5,0,0,0,18,21.5V21h4.4a.5.5,0,0,0,0-1ZM17,21.5a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1-.5-.5v-2a.5.5,0,0,1,.5-.5h2a.5.5,0,0,1,.5.5Z"></path><path d="M8.5,15h2A1.5,1.5,0,0,0,12,13.5V13H22.45a.5.5,0,1,0,0-1H12v-.5A1.5,1.5,0,0,0,10.5,10h-2A1.5,1.5,0,0,0,7,11.5V12H2.6a.5.5,0,1,0,0,1H7v.5A1.5,1.5,0,0,0,8.5,15ZM8,11.5a.5.5,0,0,1,.5-.5h2a.5.5,0,0,1,.5.5v2a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1-.5-.5Z"></path></g></svg>
                                    Filter
                                </a>
                                <div class="form-group">
                                    <select class="default-select" id="sortProducts" name="sort">
                                        <option value="latest"   {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                        <option value="popular"  {{ request('sort') == 'popular' ? 'selected' : '' }}>Popularity</option>
                                        <option value="rating"   {{ request('sort') == 'rating' ? 'selected' : '' }}>Average rating</option>
                                        <option value="low_high" {{ request('sort') == 'low_high' ? 'selected' : '' }}>Low to High</option>
                                        <option value="high_low" {{ request('sort') == 'high_low' ? 'selected' : '' }}>High to Low</option>
                                    </select>
                                </div>

                                <div class="form-group Category">
                                    <select class="default-select" id="productsPerPage">
                                        <option>Products</option>
                                        <option value="1"  {{ request('per_page') == 1  ? 'selected' : '' }}>1 Products</option>
                                        <option value="2" {{ request('per_page') == 2 ? 'selected' : '' }}>2 Products</option>
                                        <option value="14" {{ request('per_page') == 14 ? 'selected' : '' }}>14 Products</option>
                                        <option value="18" {{ request('per_page') == 18 ? 'selected' : '' }}>18 Products</option>
                                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 Products</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- PRODUCT LIST -->
                        <div id="flash-message"></div>
                        <div class="clearfix">
                            @if ($products->count())
                                <ul id="masonry" class="row g-xl-4 g-3">
                                    @foreach ($products as $product)
                                        <li class="card-container col-6 col-xl-3 col-lg-3 col-md-4 col-sm-6 wow fadeInUp">
                                            <div class="shop-card">
                                                <div class="dz-media">
                                                    <img src="{{ asset('uploads/products/' .$product->first_image_url) }}" alt="{{ $product->title }}">
                                                    <div class="shop-meta">

                                                        <a href="{{ route('product.show', $product->id) }}" 
                                                        class="btn btn-secondary btn-md btn-rounded">
                                                            <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                            <span class="d-md-block d-none">Quick View</span>
                                                        </a>

                                                        @php
                                                            $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                                                        @endphp

                                                        <div class="btn btn-primary meta-icon dz-wishicon {{ $isWishlisted ? 'active' : '' }}"
                                                            data-product-id="{{ $product->id }}">
                                                            <i class="icon feather icon-heart dz-heart"></i>
                                                            <i class="icon feather icon-heart-on dz-heart-fill"></i>
                                                        </div>

                                                        {{-- Show Price --}}

                                                        @php
                                                            // GST Percentage
                                                            $gstPercentage = 0;
                                                            if (!empty($product->gst)) {
                                                                $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                                                            }

                                                            $defaultImage = ($defaultVariant->variant_images ?? null) ?: ($product->image ?? ($product->images_list->first()->file_name ?? ''));

                                                            $basePrice = 0;
                                                            $originalBase = 0; 

                                                            if ($product->product_type == 'simple') {
                                                                $basePrice = $product->price ?? 0;
                                                                $originalBase = $product->original_price ?? $basePrice;

                                                            } elseif ($product->product_type == 'variant') {
                                                                $basePrice = $product->attributeRelations->min('price') ?? 0;
                                                                $originalBase = $product->attributeRelations->min('original_price') ?? $basePrice;
                                                            }

                                                            $finalPrice = $basePrice + ($basePrice * $gstPercentage / 100);
                                                        @endphp

                                                        <form action="{{ route('cart.add') }}" method="POST">
                                                            @csrf

                                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                            <input type="hidden" name="variant_id" id="variantId" value="{{ $defaultVariant->id ?? '' }}">

                                                            <input type="hidden" name="price" id="variantPrice" value="{{ $finalPrice }}">

                                                            <input type="hidden" name="original_price" id="variantOriginalPrice" value="{{ $originalBase }}">

                                                            <input type="hidden" name="discount" id="variantDiscount" value="{{ $defaultVariant->discount ?? 0 }}">
                                                            <input type="hidden" name="source" value="home">

                                                            <input type="hidden" name="image" id="variantImage" value="{{ $defaultImage }}">

                                                            <div class="btn btn-primary meta-icon dz-carticon addToCartBtn 
                                                                {{ in_array($product->id, $cartProductIds ?? []) ? 'in-cart' : '' }}" 
                                                                data-product-id="{{ $product->id }}" data-variant-id="{{ $defaultVariant->id ?? '' }}"
                                                                data-price="{{ $finalPrice }}" data-original-price="{{ $originalBase }}"
                                                                data-discount="{{ $defaultVariant->discount ?? 0 }}" data-image="{{ $defaultImage }}">
                                                                <i class="flaticon flaticon-basket"></i>
                                                                <i class="flaticon flaticon-basket-on dz-heart-fill"></i>
                                                            </div>
                                                        </form>
                                                    </div>	
                                                </div>

                                                <div class="dz-content">
                                                    <h5 class="title">
                                                        <a href="{{ url('product/'.$product->id) }}">
                                                            {{ \Illuminate\Support\Str::limit($product->title, 30, '...') }}
                                                        </a>
                                                    </h5>

                                                    {{-- Show Price --}}
                                                    @php
                                                        $gstPercentage = 0;
                                                        if ($product->gst) {
                                                            $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                                                        }
                                                    @endphp

                                                    @if ($product->product_type == 'simple')
                                                        <h5 class="price">₹ {{ number_format($finalPrice, 0) }}</h5>

                                                    @elseif ($product->product_type == 'variant')

                                                        @php
                                                            $minAttributePrice = $product->attributeRelations->min('price'); 
                                                            if ($minAttributePrice) {
                                                                $minPriceWithGst = $minAttributePrice + ($minAttributePrice * $gstPercentage / 100);
                                                            } else {
                                                                $minPriceWithGst = 0;
                                                            }
                                                        @endphp

                                                        <h5 class="price">₹ {{ number_format($minPriceWithGst, 0) }}</h5>
                                                    @endif
                                                </div>

                                                <div class="product-tag">
                                                    <span class="badge ">Get 20% Off</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted fs-5 fw-bold">No products found in this subcategory.</p>
                            @endif
                        </div>

                        <!-- Pagination -->
                        <div class="row page mt-3">
                            <div class="col-md-2">
                                <p class="page-text">Showing {{ $products->count() }} of {{ $products->total() }} Records</p>
                            </div>
                            <div class="col-md-10">
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('script')
<script>
    const sidebar = document.getElementById('sidebar');
    const searchInput = document.getElementById('searchInput');
    const noResults = document.getElementById('noResults');
    const allItems = sidebar.querySelectorAll('.cat-item');
    const allHeaders = sidebar.querySelectorAll('.cat-header');

    // Search filter
    searchInput.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        let count = 0;

        allItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const header = item.closest('.category')
                            .querySelector('.cat-title').textContent.toLowerCase();

            const match = text.includes(q) || header.includes(q);
            item.classList.toggle('hidden', !match);

            if (match) count++;
        });

        noResults.style.display = count === 0 ? 'block' : 'none';

        allHeaders.forEach(header => {
            let list = header.parentElement.querySelector('.cat-list');
            let hasVisible = [...list.children].some(i => !i.classList.contains('hidden'));
            list.classList.toggle('open', hasVisible);
        });
    });

    // Category submenu toggle
    sidebar.addEventListener('click', e => {
        const header = e.target.closest('.cat-header');
        if (header) {
            const list = header.parentElement.querySelector('.cat-list');
            const open = list.classList.contains('open');
            sidebar.querySelectorAll('.cat-list.open').forEach(l => l.classList.remove('open'));
            list.classList.toggle('open', !open);
        }
    });

    // Sorting
    $('#sortProducts').on('change', function () {
        let sort = $(this).val();
        let params = new URLSearchParams(window.location.search);
        params.set('sort', sort);
        params.delete('page');
        window.location.search = params.toString();
    });

    // Per Page
    $('#productsPerPage').change(function () {
        let perPage = $(this).val();
        let url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const minAvailable = 0;
        const maxAvailable = Number("{{ $maxAvailablePrice ?? 50000 }}");

        const startMin = Number("{{ request('min_price', 0) }}");
        const startMax = Number("{{ request('max_price', $maxAvailablePrice) }}");

        const slider = document.getElementById('priceSlider');

        if (!slider.noUiSlider) {
            noUiSlider.create(slider, {
                start: [startMin, startMax],
                connect: true,
                range: {
                    'min': minAvailable,
                    'max': maxAvailable
                },
                step: 1,
                tooltips: false,  
                format: {
                    to: value => Math.round(value),
                    from: value => Number(value)
                }
            });
        }

        const minInput = document.getElementById('min_price');
        const maxInput = document.getElementById('max_price');
        const minLabel = document.getElementById('slider-min-label');
        const maxLabel = document.getElementById('slider-max-label');
        const form = document.getElementById('priceFilterForm');

        slider.noUiSlider.on('update', function (values) {
            minLabel.textContent = '₹' + values[0];
            maxLabel.textContent = '₹' + values[1];
        });

        slider.noUiSlider.on('change', function (values) {
            minInput.value = values[0];
            maxInput.value = values[1];
            form.submit();
        });
    });

    $(document).ready(function() {
        $.ajax({
            url: "{{ route('wishlist.render') }}",
            type: "GET",
            success: function(html) {
                $("#wishlistArea").html(html);
            }
        });
    });

    $(document).on("click", ".dz-wishicon", function() 
    {
        let btn = $(this);
        let productId = btn.data("product-id");

        $.ajax({
            url: "/wishlist/toggle/" + productId,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },

            success: function(res) {
                if (res.status === "added") {
                    btn.addClass("active");
                    $("#flash-message").html('<div class="alert alert-success">Added to wishlist</div>');
                }

                if (res.status === "removed") {
                    btn.removeClass("active");
                    $("#flash-message").html('<div class="alert alert-danger">Removed from wishlist</div>');
                }

                setTimeout(() => {
                    $("#flash-message .alert").fadeOut();
                }, 2000);

                $("#wishlistArea").html(res.html);
                $("#wishlist-count").text(res.count);
            },

            error: function(xhr) {
                if (xhr.status === 401) {
                    $("#flash-message").html(`
                        <div class="alert alert-danger">Please login to use wishlist.</div>
                    `);

                    setTimeout(() => {
                        $("#flash-message .alert").fadeOut();
                    }, 2500);

                    btn.removeClass("active");
                }
            }
        });
    });

    $(document).on("click", ".remove-wish", function () {
        let btn = $(this);
        let productId = btn.data("id");

        $.ajax({
            url: "/wishlist/remove/" + productId,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },

            success: function (res) {
                if (res.status === "removed") {

                    btn.closest("li").fadeOut(300, function() {
                        $(this).remove();

                        $(`.dz-wishicon[data-product-id="${productId}"]`).removeClass("active");

                        let count = $("#wishlistArea li").length;
                        $("#wishlist-count").text(count);

                        if (count === 0) {
                            $("#wishlistArea").html(`
                                <li><p class="text-center fs-5 fw-bold">No items in wishlist.</p></li>
                            `);
                        }
                    });

                    // Flash message
                    $("#flash-message").html(`
                        <div class="alert alert-danger">Item removed from wishlist.</div>
                    `);

                    setTimeout(() => {
                        $("#flash-message .alert").fadeOut();
                    }, 2000);
                }
            },

            error: function () {
                $("#flash-message").html(`
                    <div class="alert alert-danger">Something went wrong.</div>
                `);
            }
        });
    });

    $(document).on('click', '.addToCartBtn', function(e){
        e.preventDefault();
        let btn = $(this);

        let productId      = btn.data('product-id');
        let variantId      = btn.data('variant-id');
        let price          = btn.data('price');
        let originalPrice  = btn.data('original-price');
        let discount       = btn.data('discount');
        let image          = btn.data('image');

        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                variant_id: variantId,
                price: price,
                original_price: originalPrice,
                discount: discount,
                image: image,
                quantity: 1
            },
            success: function(res){
                if(res.status === 'success'){
                    $('.cart-count').text(res.cartCount);

                    if(res.cartCount !== undefined){
                        $('.cart-count').text(res.cartCount);
                    }

                    if(res.cartItems){
                        updateHeaderCart(res.cartItems);
                    }

                    if (res.cartTotalHtml) {
                        $("#cart-total-section").html(
                            $(res.cartTotalHtml).find("#cart-total-section").html()
                        );
                    }

                    // Update hidden inputs in the form
                    $('#variantId').val(variantId);
                    $('#variantPrice').val(price);
                    $('#variantOriginalPrice').val(originalPrice);
                    $('#variantDiscount').val(discount);
                    $('#variantImage').val(image.split('/').pop());

                    // Mark button as in-cart
                    btn.addClass('in-cart');
                    // Flash message
                    $("#flash-message").html(`
                        <div class="alert alert-success">Product add to cart.</div>
                    `);

                    setTimeout(() => {
                        $("#flash-message .alert").fadeOut();
                    }, 2000);
                }
            },
            error: function(){
                showCartMessage('Something went wrong.', true);
            }
        });
    });

    function updateHeaderCart(cartItems){
        let listHTML = '';
        let subtotal = 0;

        if (!cartItems || cartItems.length === 0) {

            listHTML = '<li class="text-center p-3">Your cart is empty.</li>';

        } else {
            cartItems.forEach(function(item){
                let img = item.image ? '/uploads/products/' + item.image :
                        (item.product && item.product.image ? '/uploads/products/' + item.product.image :
                        '/images/default-product.png');

                let name = item.product ? item.product.title : 'Product';

                // Calculate item subtotal
                let itemSubtotal = parseFloat(item.price) * parseInt(item.quantity);

                subtotal += itemSubtotal;

                listHTML += `
                    <li data-id="${item.id}">
                        <div class="cart-widget">

                            <div class="dz-media me-3">
                                <img src="${img}" width="60" />
                            </div>

                            <div class="cart-content">
                                <h6 class="title">
                                    <a href="/product/${item.product ? item.product.id : '#'}">${name}</a>
                                </h6>

                                <div class="d-flex align-items-center">
                                
                                    <div class="quantity btn-quantity style-1 me-3">
                                        <div class="input-group bootstrap-touchspin">
                                            <input type="text" value="${item.quantity}" min="1"
                                                class="form-control quantity-input"
                                                data-id="${item.id}" style="display:block;">

                                            <span class="input-group-btn-vertical">
                                                <button class="btn btn-default bootstrap-touchspin-up quantity-plus" data-id="${item.id}">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                                <button class="btn btn-default bootstrap-touchspin-down quantity-minus" data-id="${item.id}">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <h6 class="dz-price mb-0">₹${itemSubtotal.toFixed(2)}</h6>

                                </div>
                            </div>

                            <a href="javascript:void(0);" class="dz-close removeCartItem" data-id="${item.id}">
                                <i class="ti-close"></i>
                            </a>

                        </div>
                    </li>
                `;
            });
        }

        // Update cart items
        $('.sidebar-cart-list').html(listHTML);

        // Format subtotal like Blade
        let subtotalHTML = `
            <div class="cart-total d-flex justify-content-between">
                <h5 class="mb-0">Subtotal:</h5>
                <h5 class="mb-0">₹ ${subtotal.toLocaleString()}</h5>
            </div>
        `;

        $('#cart-total-section').html(subtotalHTML);
    }

    // Show temporary message
    function showCartMessage(message, isError = false){
        let alertClass = isError ? 'alert-danger' : 'alert-success';
        let alertBox = $(`
            <div class="cart-message alert ${alertClass}" 
                style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                ${message}
            </div>
        `);

        $('body').append(alertBox);

        setTimeout(function(){
            alertBox.fadeOut(500, function(){ $(this).remove(); });
        }, 2500);
    }

    $(document).on("click", ".removeCartItem", function () {

        let btn = $(this);
        let cartId = btn.data("id");

        $.ajax({
            url: "/cart/remove/" + cartId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },

            success: function (res) {
                if (res.status === "removed") {

                    if (res.product_id) {
                        $(`.addToCartBtn[data-product-id="${res.product_id}"]`)
                            .removeClass("active in-cart");
                    }

                    if (res.subtotal !== undefined) {
                        if (Number(res.subtotal) <= 0) {

                            $("#cart-total-section").html(`
                                <div class="cart-total text-center">
                                    <h5 class="mb-0 fw-bold">Your cart is empty.</h5>
                                </div>
                            `);

                            $(".sidebar-cart-list").html(`
                                <li><p class="text-center fs-5 fw-bold">Your cart is empty.</p></li>
                            `);

                        } else {

                            $("#cart-total-section").html(`
                                <div class="cart-total d-flex justify-content-between">
                                    <h5 class="mb-0">Subtotal:</h5>
                                    <h5 class="mb-0">₹ ${Number(res.subtotal).toLocaleString()}</h5>
                                </div>
                            `);
                        }
                    }

                    if (res.count !== undefined) {
                        $("#cart-count").text(res.count);
                        $(".cart-count").text(res.count);
                    }

                    btn.closest("li").fadeOut(200, function () {
                        $(this).remove();

                        // If no items left
                        if ($(".sidebar-cart-list li").length === 0) {
                            $(".sidebar-cart-list").html(`
                                <li><p class="text-center fs-5 fw-bold">Your cart is empty.</p></li>
                            `);
                        }
                    });

                    // Remove active state from add-to-cart button (on product detail page)
                    if (res.product_id) {
                        $(`.addToCartBtn[data-product-id="${res.product_id}"]`)
                            .removeClass("in-cart");
                    }

                    // Flash message
                    $("#flash-message").html(`
                        <div class="alert alert-danger">Item removed from cart.</div>
                    `);

                    setTimeout(() => {
                        $("#flash-message .alert").fadeOut();
                    }, 2000);
                }
            },

            error: function () {
                $("#flash-message").html(`
                    <div class="alert alert-danger">Something went wrong.</div>
                `);
            }
        });
    });

    //// Cart Qunatity Update
    $(document).on('change', '.quantity-input', function () {

        let input = $(this);
        let quantity = parseInt(input.val());
        let cartItemId = input.data('id');

        if (quantity < 1) quantity = 1;

        $.ajax({
            url: '/cart/update/' + cartItemId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },

            success: function (res) {

                // Update item price instantly
                if (res.itemTotal) {
                    input.closest('.quantity-wrapper')
                        .find('.dz-price')
                        .text("₹ " + res.itemTotal);
                }

                // Update subtotal instantly
                if (res.subtotal) {
                    $("#cart-total-section").html(`
                        <div class="cart-total d-flex justify-content-between">
                            <h5 class="mb-0">Subtotal:</h5>
                            <h5 class="mb-0">₹ ${res.subtotal}</h5>
                        </div>
                    `);
                }

                // Update cart count
                if (res.count !== undefined) {
                    $(".cart-count").text(res.count);
                }
            }
        });
    });

</script>
@endsection
