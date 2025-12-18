@extends('landing.layout')

@section('content')
    <div class="page-wraper">
        <div class="page-content bg-light">
            <div class="d-sm-flex justify-content-between container-fluid py-3">
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"> Home</a></li>
                        <li class="breadcrumb-item">{{ $category->category_name }}</li>
                        <li class="breadcrumb-item active">
                            <a href="{{ url('/subcategory/'.$subcategory->slug) }}">{{ $subcategory->category_name }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
		
            <section class="content-inner py-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <div class="dz-product-detail sticky-top">
                                <div class="swiper-btn-center-lr">
                                    <div class="swiper product-gallery-swiper2 rounded">

                                        @if(in_array($product->product_type, ['variant', 'adult']))
                                            <div class="swiper-slide" id="lightgallery2">
                                                <div class="dz-media DZoomImage">
                                                    <a class="mfp-link lg-item"
                                                    href="{{ asset('uploads/products/' . $product->display_image) }}"
                                                    data-src="{{ asset('uploads/products/' . $product->display_image) }}">
                                                        <i class="feather icon-maximize dz-maximize top-left"></i>
                                                    </a>

                                                    <img id="dynamicImage"
                                                        src="{{ asset('uploads/products/' . $product->display_image) }}"
                                                        alt="{{ $product->title }}">
                                                </div>
                                            </div>

                                        @else
                                            <div class="swiper-wrapper" id="lightgallery2">
                                                @foreach($product->images_list as $img)
                                                    <div class="swiper-slide">
                                                        <div class="dz-media DZoomImage">
                                                            <a class="mfp-link lg-item"
                                                            href="{{ asset('uploads/products/'.$img->file_name) }}"
                                                            data-src="{{ asset('uploads/products/'.$img->file_name) }}">
                                                                <i class="feather icon-maximize dz-maximize top-left"></i>
                                                            </a>
                                                            <img id="dynamicImage" src="{{ asset('uploads/products/'.$img->file_name) }}" alt="">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- <div class="swiper-wrapper" id="lightgallery2">
                                            @foreach($product->images_list as $img)
                                                <div class="swiper-slide">
                                                    <div class="dz-media DZoomImage">
                                                        <a class="mfp-link lg-item"
                                                        href="{{ asset('uploads/products/'.$img->file_name) }}"
                                                        data-src="{{ asset('uploads/products/'.$img->file_name) }}">
                                                            <i class="feather icon-maximize dz-maximize top-left"></i>
                                                        </a>
                                                        <img id="dynamicImage" src="{{ asset('uploads/products/'.$img->file_name) }}" alt="">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div> --}}
                                    </div>
                                </div>							
                            </div>	
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <div class="row">
                                <div class="col-xl-7">
                                    <div class="dz-product-detail style-2 p-t20 ps-0">
                                        <div class="dz-content">
                                            <div class="dz-content-footer">
                                                <div class="dz-content-start">
                                                    {{-- <span class="badge bg-secondary mb-2">SALE 20% Off</span> --}}
                                                    <h4 class="title mb-1">{{ $product->title }}</h4>
                                                    <div class="review-num">
                                                        <ul class="dz-rating me-2">
                                                            @php
                                                                $avg = round($product->averageRating());
                                                            @endphp

                                                            @for($i = 1; $i <= 5; $i++)
                                                                <li class="{{ $i <= $avg ? 'star-fill' : '' }}">
                                                                    <i class="flaticon-star-1"></i>
                                                                </li>
                                                            @endfor
                                                        </ul>

                                                        <span class="text-secondary me-2">
                                                            {{ number_format($product->averageRating(), 1) }} Rating
                                                        </span>

                                                        <a href="javascript:void(0);">
                                                            ({{ $product->reviewCount() }} customer reviews)
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="dz-info">
                                                <ul>
                                                    @php
                                                        // ==========================
                                                        // GET GST %
                                                        // ==========================
                                                        $gstPercentage = 0;
                                                        if (!empty($product->gst) && !empty($product->gst->gst_percentage)) {
                                                            $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                                                        }

                                                        // ==========================
                                                        // PRICE CALCULATION
                                                        // ==========================
                                                        $basePrice = 0;
                                                        $baseOriginal = null;

                                                        if ($product->product_type == 'simple') {

                                                            $basePrice = $product->price ?? 0;
                                                            $baseOriginal = $product->original_price ?? null;

                                                        } else {

                                                            $basePrice = $product->attributeRelations->min('price') ?? 0;
                                                            $baseOriginal = $product->attributeRelations->min('original_price') ?? null;
                                                        }

                                                        $priceWithGst = $basePrice + (($basePrice * $gstPercentage) / 100);

                                                        $discount = null;

                                                        if (!empty($baseOriginal) && $baseOriginal > $priceWithGst) {
                                                            $discount = round((($baseOriginal - $priceWithGst) / $baseOriginal) * 100);
                                                        }
                                                    @endphp

                                                    <li>
                                                        <strong>Special price:</strong><br>

                                                        <div class="d-flex align-items-center" style="gap:10px;">

                                                            <h5 id="dynamicPrice" class="price" style="margin:0; font-size:22px; font-weight:600;">
                                                                ₹ {{ number_format($priceWithGst, 0) }}
                                                            </h5>

                                                            <!-- STATIC original price (show only first time) -->
                                                            @if($baseOriginal)
                                                                <small id="staticOriginal" style="text-decoration: line-through; color:#888; font-size:16px;">
                                                                    ₹ {{ number_format($baseOriginal, 0) }}
                                                                </small>
                                                            @endif

                                                            @if($discount)
                                                                <span id="staticDiscount" style="color:green; font-weight:600; margin-left:5px;">
                                                                    {{ $discount }}% Off
                                                                </span>
                                                            @endif

                                                            <span id="dynamicOriginalPrice" style="display:none; text-decoration: line-through; color:#888; font-size:16px;">
                                                            </span>

                                                            <span id="dynamicDiscount" style="display:none; color:green; font-size:16px; font-weight:600;">
                                                            </span>
                                                        </div>
                                                        <small id="stockText" style="color:red; display:none;"></small>
                                                    </li>
                                                </ul>
                                            </div>

                                            <p class="para-text">
                                                @php
                                                    $cleanDesc = html_entity_decode(strip_tags($product->description ?? 'N/A'));
                                                    $description = Str::words($cleanDesc, 50, '...');
                                                @endphp
                                                <p>{{ $description ?? 'N/A' }}</p>
                                                {{-- {!! $product->description !!} --}}
                                            </p>
                                            
                                            {{-- @php
                                                $cheapestAttr = $product->attributeRelations->sortBy('price')->first();
                                            @endphp --}}

                                            <div class="row">
                                                @if ($product->product_type == 'variant' || $product->product_type == 'adult')
                                                    <div class="col-md-4">
                                                        <label>Pack Of*</label>

                                                        {{-- <select id="quantitySelect" name="variant_id" class="form-select w-100">
                                                            @foreach ($product->attributeRelations as $attr)
                                                                @if($attr->quantity > 0)
                                                                    <option value="{{ $attr->id }}"
                                                                        data-price="{{ $attr->price }}"
                                                                        data-original="{{ $attr->original_price }}"
                                                                        data-pack="{{ $attr->value }}"      
                                                                        data-stock="{{ $attr->quantity }}"  
                                                                        {{ $cheapestAttr && $cheapestAttr->id == $attr->id ? 'selected' : '' }}>
                                                                        {{ $attr->value }} Pieces
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                            <small id="stockText" style="color:red; display:none;"></small>
                                                            <input type="hidden" name="variant_id" id="variantId">

                                                        </select> --}}

                                                        <select id="quantitySelect" class="form-select w-100">
                                                            <option selected disabled>Select Quantity</option>
                                                            @foreach ($product->attributeRelations as $attr)
                                                                <option value="{{ $attr->id }}" data-quantity="{{ $attr->value }}" data-size="{{ explode('-', $attr->value)[0] }}" data-stock="{{ $attr->quantity }}">
                                                                    {{ $attr->value }} Pieces
                                                                </option>
                                                            @endforeach
                                                            
                                                        </select>

                                                        <small id="stockText" style="color:red; display:none;"></small>
                                                    </div>

                                                    @if ($product->product_type == 'variant' && !preg_match('/\([A-Za-z]+-.*\)/', $product->title))
                                                        <div class="col-md-4">
                                                            <label>Size Of*</label>
                                                            <select id="sizeSelect" class="form-select w-100">
                                                                <option value="" selected disabled>Select Size</option>

                                                                @foreach ($product->attributeRelations as $attr)
                                                                    @php $size = explode('-', $attr->value)[0]; @endphp

                                                                    <option value="{{ $attr->id }}" data-quantity="{{ $attr->quantity }}">
                                                                        {{ explode('-', $attr->value)[0] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <!-- User enters delivery pincode -->
                                                <div class="col-md-4">
                                                    <div class="m-b25">
                                                        <label class="label-title">Delivery To</label>
                                                        <div class="form-group m-b25">
                                                            <input name="pincode" id="pincodeInput" class="form-control m-b15 @error('pincode') is-invalid @enderror" placeholder="Enter Pincode">
                                                            @error('pincode')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>		
                                            
                                            <div class="dz-info">

                                                <ul>
                                                    <li><strong>SKU:</strong></li>
                                                    <li>{{ $product->product_item_code ?? 'N/A' }}</li>
                                                </ul>
                                                <ul>
                                                    <li><strong>Category:</strong></li>
                                                    <li>{{ $category->category_name ?? 'N/A' }}</li>												
                                                </ul>
                                                <ul>
                                                    <li><strong>Tags:</strong></li>
                                                    <li>{{ $product->tags ?? 'N/A' }} </li>
                                                </ul>
                                                <ul>
                                                    <li><strong>Manufacture Date:</strong></li>
                                                    <li>{{ $product->manufacture_date ? \Carbon\Carbon::parse($product->manufacture_date)->format('Y-m-d') : 'N/A' }} </li>
                                                </ul>
                                                <ul>
                                                    <li><strong>Expiry Date:</strong></li>
                                                    <li>{{ $product->expiry_date?->format('Y-m-d') ?? 'N/A' }}</li>
                                                </ul>
                                                {{-- <ul class="social-icon">
                                                    <li><strong>Share:</strong></li>
                                                    <li>
                                                        <a href="https://www.facebook.com/dexignzone" target="_blank">
                                                            <i class="fa-brands fa-facebook-f"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://www.linkedin.com/showcase/3686700/admin/" target="_blank">
                                                            <i class="fa-brands fa-linkedin-in"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://www.instagram.com/dexignzone/" target="_blank">
                                                            <i class="fa-brands fa-instagram"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://twitter.com/dexignzones" target="_blank">
                                                            <i class="fa-brands fa-twitter"></i>
                                                        </a>
                                                    </li>
                                                </ul> --}}
                                            </div>
                                        </div>
                                        {{-- <div class="banner-social-media">
                                            <ul>
                                                <li>
                                                    <a href="https://www.instagram.com/dexignzone/">Instagram</a>
                                                </li>
                                                <li>
                                                    <a href="https://www.facebook.com/dexignzone">Facebook</a>
                                                </li>
                                                <li>
                                                    <a href="https://twitter.com/dexignzones">twitter</a>
                                                </li>
                                            </ul>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-xl-5">
                                    <div class="cart-detail">
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary w-100 m-b20">Manufacturing Information</a>
                                        <div class="icon-bx-wraper style-4 m-b15">
                                            <div class="icon-bx">
                                                <i class="flaticon flaticon-ship"></i>
                                            </div>
                                            <div class="icon-content">
                                                <span class=" font-14">Country of Origin</span>
                                                <h6 class="dz-title">India</h6>
                                            </div>
                                        </div>
                                        <div class="icon-bx-wraper style-4 m-b30">
                                            <div class="icon-bx">
                                                {{-- Brand Logo --}}
                                                <a href="{{ route('brand.products', $product->brand->slug) }}" class="text-decoration-none">
                                                    @if($product->brand)
                                                        <img src="{{ asset('storage/' . $product->brand->brand_logo) }}" alt="{{ $product->brand->brand_name }}">
                                                    @else
                                                        <img src="{{ asset('images/default-brand.png') }}" alt="Brand">
                                                    @endif
                                                </a>
                                            </div>

                                            <div class="icon-content">
                                                <a href="{{ route('brand.products', $product->brand->slug) }}" class="text-decoration-none">
                                                    <h6 class="dz-title">{{ $product->brand->brand_name ?? 'Unknown Brand' }}</h6>
                                                    @php
                                                        $cleanDesc = html_entity_decode(strip_tags($product->brand->description ?? 'N/A'));
                                                        $shortDesc = Str::words($cleanDesc, 20, '...');
                                                    @endphp
                                                    <p>{{ $shortDesc ?? 'N/A' }}</p>
                                                </a>
                                            </div>  
                                        </div>

                                        <div class="save-text" id="saveBox" style="display:none;">
                                            <i class="icon feather icon-check-circle" id="saveIcon"></i>
                                            <span id="dynamicSaving" class="m-l10" style="color:#444;"></span>
                                        </div>

                                        <table>
                                            <tbody>
                                                <tr class="total">
                                                    <td>
                                                        <h6 class="mb-0">Total</h6>
                                                    </td>
                                                    <td class="price">
                                                        @php
                                                            $gstPercentage = 0;
                                                            if ($product->gst) {
                                                                $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                                                            }

                                                            if ($product->product_type == 'simple') {
                                                                $basePrice = $product->price ?? 0;

                                                            } else {
                                                                $basePrice = $product->attributeRelations->min('price') ?? 0;
                                                            }

                                                            $totalPriceWithGst = $basePrice + ($basePrice * $gstPercentage / 100);
                                                        @endphp

                                                        <h5 id="TotalPrice" class="price">
                                                            ₹ {{ number_format($totalPriceWithGst, 0) }}
                                                        </h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a href="javascript:void(0)" class="btn btn-outline-secondary btn-icon m-b20 wishlistBtn" data-product-id="{{ $product->id }}" data-redirect="{{ route('wishlist.index') }}">
                                            @php
                                                $already = $wishlistItems->contains($product->id);
                                            @endphp
                                            <svg class="heartIcon" width="19" height="17" viewBox="0 0 19 17" xmlns="http://www.w3.org/2000/svg">
                                                <path class="heartPath" d="M9.24805 16.9986C8.99179 16.9986 8.74474 16.9058 8.5522 16.7371C7.82504 16.1013 7.12398 15.5038 6.50545 14.9767L6.50229 14.974C4.68886 13.4286 3.12289 12.094 2.03333 10.7794C0.815353 9.30968 0.248047 7.9162 0.248047 6.39391C0.248047 4.91487 0.755203 3.55037 1.67599 2.55157C2.60777 1.54097 3.88631 0.984375 5.27649 0.984375C6.31552 0.984375 7.26707 1.31287 8.10464 1.96065C8.52734 2.28763 8.91049 2.68781 9.24805 3.15459C9.58574 2.68781 9.96875 2.28763 10.3916 1.96065C11.2292 1.31287 12.1807 0.984375 13.2197 0.984375C14.6098 0.984375 15.8885 1.54097 16.8202 2.55157C17.741 3.55037 18.248 4.91487 18.248 6.39391C18.248 7.9162 17.6809 9.30968 16.4629 10.7792C15.3733 12.094 13.8075 13.4285 11.9944 14.9737C11.3747 15.5016 10.6726 16.1001 9.94376 16.7374C9.75136 16.9058 9.50417 16.9986 9.24805 16.9986ZM5.27649 2.03879C4.18431 2.03879 3.18098 2.47467 2.45108 3.26624C1.71033 4.06975 1.30232 5.18047 1.30232 6.39391C1.30232 7.67422 1.77817 8.81927 2.84508 10.1066C3.87628 11.3509 5.41011 12.658 7.18605 14.1715L7.18935 14.1743C7.81021 14.7034 8.51402 15.3033 9.24654 15.9438C9.98344 15.302 10.6884 14.7012 11.3105 14.1713C13.0863 12.6578 14.6199 11.3509 15.6512 10.1066C16.7179 8.81927 17.1938 7.67422 17.1938 6.39391C17.1938 5.18047 16.7858 4.06975 16.045 3.26624C15.3152 2.47467 14.3118 2.03879 13.2197 2.03879C12.4197 2.03879 11.6851 2.29312 11.0365 2.79465C10.4585 3.24179 10.0558 3.80704 9.81975 4.20255C9.69835 4.40593 9.48466 4.52733 9.24805 4.52733C9.01143 4.52733 8.79774 4.40593 8.67635 4.20255C8.44041 3.80704 8.03777 3.24179 7.45961 2.79465C6.811 2.29312 6.07643 2.03879 5.27649 2.03879Z" fill="{{ $already ? 'red' : 'black' }}"></path>
                                            </svg>
                                            Add To Wishlist
                                        </a>

                                        @php
                                            $defaultPrice = 0;

                                            $gstPercentage = 0;
                                            if ($product->gst) {
                                                $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                                            }

                                            if ($product->product_type == 'simple') {
                                                $defaultPrice = $product->price ?? 0;
                                            } elseif (in_array($product->product_type, ['variant', 'adult'])) {
                                                if (!empty($defaultVariant->variant_price)) {
                                                    $defaultPrice = $defaultVariant->variant_price;
                                                } 
                                                elseif (isset($product->attributeRelations) && $product->attributeRelations->count() > 0) {
                                                    $defaultPrice = $product->attributeRelations->min('price');
                                                }
                                            }

                                            $defaultPriceWithGst = $defaultPrice + ($defaultPrice * $gstPercentage / 100);
                                            
                                            $defaultImage = $defaultVariant->variant_images ?? $product->image ?? ($product->images_list->first()->file_name ?? '');
                                        @endphp

                                        <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            <input type="hidden" name="variant_id" id="variantId"
                                                value="{{ $defaultVariant->id ?? '' }}">

                                            <input type="hidden" name="price" id="variantPrice"
                                                value="{{ $defaultPriceWithGst ?? $defaultPrice }}">

                                            <input type="hidden" name="original_price" id="variantOriginalPrice"
                                                value="{{ $defaultVariant->original_price ?? 0 }}">

                                            <input type="hidden" name="discount" id="variantDiscount"
                                                value="{{ $defaultVariant->discount ?? 0 }}">

                                            <input type="hidden" name="image" id="variantImage"
                                            value="{{ $defaultVariant->variant_images ?? $defaultImage }}">

                                            <input type="hidden" name="pincode" id="hiddenPincode">
                                            <input type="hidden" name="source" value="product_detail">

                                            <button type="submit" class="btn btn-secondary w-100" id="addToCartBtn" 
                                            data-product-type="{{ $product->product_type }}"
        data-stock="{{ $product->stock_quantity ?? 0 }}"

        >ADD TO CART</button>
                                        </form>
                                    </div>	
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
		
            <section class="content-inner-3 pb-0"> 
                <div class="container">
                    <div class="product-description">
                        <div class="dz-tabs">					
                            <ul class="nav nav-tabs center" id="myTab1" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Description</button>
                                </li>
                                @if($product->userHasPurchased())
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Reviews ({{ $product->reviewCount() }})</button>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                    <div class="detail-bx text-center">
                                        <h5 class="title">{{ $product->title }}</h5>
                                        <p class="para-text">
                                            {!! $product->description !!}
                                        </p>
                                        {{-- HIGHLIGHTS--}}
                                        @if($product->highlights)
                                            <ul class="feature-detail">
                                                @foreach(explode(',', $product->highlights) as $highlight)
                                                    <li>
                                                        <i class="icon feather icon-check"></i>
                                                        <h5>{{ trim($highlight) }}</h5>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="product-specification">
                                        <h4 class="specification-title">Specifications</h4>
                                    </div>

                                    <div class="row">
                                        @php
                                            $specs = [];

                                            if (!empty($product->specifications)) {
                                                if (is_string($product->specifications)) {
                                                    $specs = json_decode($product->specifications, true);
                                                } elseif (is_array($product->specifications)) {
                                                    $specs = $product->specifications;
                                                }
                                            }
                                        @endphp

                                        @if (!empty($specs))
                                            <div class="col-lg-12 m-lg-b0 m-md-b30">
                                                <ul class="specification-list m-b40">
                                                    @foreach($specs as $spec)
                                                        <li class="list-info">
                                                            {{ $spec['label'] ?? '' }}
                                                            <span>{{ $spec['value'] ?? '' }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <p class="text-muted">No specifications available.</p>
                                        @endif
                                    </div>

                                    <div class="row g-lg-4 g-3">
                                        @foreach($product->bottom_images_list as $img)
                                            <div class="col-xl-4 col-md-4 col-sm-4 col-6">
                                                <div class="related-img dz-media">
                                                    <img src="{{ asset('uploads/products/' . $img->file_name) }}" alt="{{ $product->title }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                    <div class="clear" id="comment-list">
                                        <div class="post-comments comments-area style-1 clearfix">
                                            <h4 class="comments-title mb-2">Comments ({{ $product->reviewCount() }})</h4>
                                            <div id="comment">
                                                @include('landing.partials.reviews', ['reviews' => $reviews])
                                            </div>

                                            <div class="default-form comment-respond style-1" id="respond">
                                                {{-- <h4 class="comment-reply-title mb-2" id="reply-title">Good Comments</h4>
                                                <p class="dz-title-text">There are many variations of passages of Lorem Ipsum available.</p> --}}
                                                <div id="flash-message"></div>
                                                @if(!$alreadyReviewed)
                                                    <form id="reviewForm" class="comment-form" novalidate>
                                                        @csrf

                                                        <div class="comment-form-rating d-flex">
                                                            <label class="pull-left m-r10 m-b20 text-secondary">Your Rating</label>
                                                            <div class="rating-widget">
                                                                <div class="rating-stars">
                                                                    <ul id="stars">
                                                                        @for($i=1; $i<=5; $i++)
                                                                            <li class="star" data-value="{{ $i }}">
                                                                                <i class="fas fa-star fa-fw"></i>
                                                                            </li>
                                                                        @endfor
                                                                    </ul>
                                                                </div>
                                                                <div id="ratingError" class="text-danger mt-1" style="font-size:14px;"></div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" id="ratingInput" name="rating">

                                                        <p class="comment-form-author w-100">
                                                            <input id="name" placeholder="Author" name="uname" type="text">
                                                        </p>

                                                        <p class="comment-form-email w-100">
                                                            <input id="email" placeholder="Email" name="uemail" type="email">
                                                        </p>

                                                        <p class="comment-form-comment">
                                                            <textarea id="comments" placeholder="Type Comment Here" name="comment" cols="45" rows="3"></textarea>
                                                        </p>

                                                        <p class="col-md-12 form-submit">
                                                            <button type="submit" class="submit btn btn-secondary btnhover3 filled">
                                                                Submit Now
                                                            </button>
                                                        </p>
                                                    </form>
                                                @else
                                                    <div id="reviewAlert" class="alert alert-info">
                                                        You have already reviewed this product.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
		
            <section class="content-inner-1 overflow-hidden">
                <div class="container">
                    @if($relatedProducts->count() > 0)
                        <div class="section-head style-2 d-md-flex justify-content-between align-items-center">
                            <div class="left-content">
                                <h2 class="title mb-0">Related products</h2>
                            </div>
                            <a href="{{ route('subcategory.products', $subcategory->slug) }}" 
                            class="text-secondary font-14 d-flex align-items-center gap-1">
                                See all products
                                <i class="icon feather icon-chevron-right font-18"></i>
                            </a>
                        </div>
                        
                        <div class="swiper-btn-center-lr">
                            <div class="swiper swiper-four">
                                <div class="swiper-wrapper">
                                    @foreach ($relatedProducts as $item)
                                        <div class="swiper-slide">
                                            <div class="shop-card style-1">
                                                <div class="dz-media">
                                                    {{-- Show First Image Only --}}
                                                    <img src="{{ asset('uploads/products/' . $item->first_image_url) }}" 
                                                        alt="{{ $item->product_name }}">

                                                    <div class="shop-meta">
                                                        <a href="{{ route('product.show', $item->id) }}" 
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

                                                            } elseif ($product->product_type == 'variant' || $product->product_type == 'adult') {
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

                                                            @if(in_array($product->id, $cartProductIds ?? []))
                                                                <div class="btn btn-primary meta-icon dz-carticon in-cart"
                                                                    data-product-id="{{ $product->id }}"
                                                                    data-variant-id="{{ $defaultVariant->id ?? '' }}"
                                                                    data-price="{{ $finalPrice }}"
                                                                    data-original-price="{{ $originalBase }}"
                                                                    data-discount="{{ $defaultVariant->discount ?? 0 }}"
                                                                    data-image="{{ $defaultImage }}">

                                                                    <i class="flaticon flaticon-basket"></i>
                                                                    <i class="flaticon flaticon-basket-on dz-heart-fill"></i>
                                                                </div>
                                                            @endif
                                                        </form>

                                                    </div>
                                                </div>

                                                <div class="dz-content">
                                                    <h5 class="title">
                                                        <a href="{{ route('product.show', $item->id) }}">
                                                            {{ $item->title }}
                                                        </a>
                                                    </h5>
                                                    <h5 class="price">₹ {{ number_format($priceWithGst, 0) }}</h5>                                                </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            <div class="pagination-align">
                                <div class="tranding-button-prev btn-prev">
                                    <i class="flaticon flaticon-left-chevron"></i>
                                </div>
                                <div class="tranding-button-next btn-next">
                                    <i class="flaticon flaticon-chevron"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- No Related Products --}}
                        <div class="text-center py-5">
                            <h4 class="text-muted">No related products</h4>
                        </div>
                    @endif
                </div>
            </section>
	    </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#offcanvasRight").on("shown.bs.offcanvas", function () {
                $.ajax({
                    url: "{{ route('wishlist.render') }}",
                    type: "GET",
                    success: function (html) {
                        $("#wishlistArea").html(html);
                    },
                    error: function () {
                        $("#wishlistArea").html(`
                            <li><p class="text-center text-danger">Failed to load wishlist.</p></li>
                        `);
                    }
                });
            });
        });

        // Only for SIMPLE products(STOCK QUANTITY)
        $(document).ready(function () {
            let productType = $('#addToCartBtn').data('product-type');

            if (productType === 'simple') {
                let stock = parseInt($('#addToCartBtn').data('stock'), 10) || 0;

                if (stock === 0) {
                    $('#stockText').html('<strong>Out of Stock</strong>').addClass('text-danger fw-bold').show();
                    $('#addToCartBtn').prop('disabled', true).addClass('disabled').css('cursor', 'not-allowed');

                } else if (stock <= 5) {
                    $('#stockText').html('<strong>Hurry Up!</strong> Only ' + stock + ' left').addClass('text-danger fw-bold').show();
                    $('#addToCartBtn').prop('disabled', false).removeClass('disabled').css('cursor', 'pointer');

                } else {
                    $('#stockText').hide();
                    $('#addToCartBtn').prop('disabled', false).removeClass('disabled').css('cursor', 'pointer');
                }
            }
        });

        // When Quantity is changed
        $('#quantitySelect').on('change', function () {
            let selectedQuantity = $(this).find(':selected').data('quantity');

            let stock = $('option:selected', this).data('stock');

            if (stock === 0) {
                $('#stockText').html('<strong>Out of stock.</strong>').removeClass('text-danger').addClass('text-danger fw-bold').show();
                $('#addToCartBtn').prop('disabled', true).addClass('disabled').css('cursor', 'not-allowed');

            } else if (stock <= 5) {
                $('#stockText').html('<strong>Hurry Up!</strong> Only ' + stock + ' left').removeClass('text-danger').addClass('text-danger fw-bold').show();
                $('#addToCartBtn').prop('disabled', false).removeClass('disabled').css('cursor', 'pointer');

            } else {
                $('#stockText').hide();
                $('#addToCartBtn').prop('disabled', false).removeClass('disabled').css('cursor', 'pointer');
            }

            $('#sizeSelect option').each(function () {
                let optionQuantity = $(this).data('quantity');

                if (!optionQuantity) {
                    $(this).show();  
                } 
                else if (optionQuantity == selectedQuantity) {
                    $(this).show();  
                } 
                else {
                    $(this).hide();  
                }
            });
 
            $('#sizeSelect').val('');
        });

        $('#addToCartForm').on('submit', function(e) {
            $('#hiddenPincode').val($('#pincodeInput').val());
        });

        let productType = "{{ $product->product_type }}";
        function safeBind(selector, event, handler) {
            const el = $(selector);
            if (el.length) el.on(event, handler);
        }

        safeBind('#quantitySelect', 'change', function () {
            const selectedId = $(this).val();
            updateVariant(selectedId);
        });

        safeBind('#sizeSelect', 'change', function () {
            const selectedId = $(this).val();
            updateVariant(selectedId);
        });

        if (productType === 'adult') {
            $('#sizeSelect').closest('.col-md-4').hide(); 
        }

        // Reusable function
        function updateVariant(selectedId) {
            if (!selectedId) return;

            $.ajax({
                url: '/get-attribute-image/' + selectedId,
                type: 'GET',
                success: function(res) {
                    $('#dynamicPrice').text('₹' + Number(res.price).toLocaleString());
                    $('#TotalPrice').text('₹' + Number(res.price).toLocaleString());

                    $('#dynamicOriginalPrice').text('₹' + Number(res.original_price).toLocaleString()).show();
                    $('#dynamicDiscount').text(res.discount + '% off').show();

                    $('#staticOriginal').hide();
                    $('#staticDiscount').hide();

                    $('#dynamicImage').attr('src', res.image);

                    // Add to cart hidden fields
                    $('#variantId').val(res.id);
                    $('#variantPrice').val(res.price);
                    $('#variantOriginalPrice').val(res.original_price);
                    $('#variantDiscount').val(res.discount);

                    let filename = res.image.split('/').pop();
                    $('#variantImage').val(filename);
                    $('#variantId').val(res.id); // ✅ NOW CORRECT
                    // $('#stockText').text(res.stock + ' pieces available').show();

                    // Saving amount
                    let savingAmount = res.original_price - res.price;
                    if (savingAmount > 0) {
                        $('#dynamicSaving').text('You will save ₹ ' + Number(savingAmount).toLocaleString());
                        $('#saveBox').show();
                    } else {
                        $('#saveBox').hide();
                    }
                }
            });
        }

        // $('#sizeSelect').on('change', function () {
        //     let selectedId = $(this).val();

        //     if (selectedId) {
        //         $.ajax({
        //             url: '/get-attribute-image/' + selectedId,
        //             type: 'GET',
        //             success: function (res) {

        //                 $('#dynamicPrice').text('₹' + Number(res.price).toLocaleString());
        //                 $('#TotalPrice').text('₹' + Number(res.price).toLocaleString());

        //                 $('#dynamicOriginalPrice').text('₹' + Number(res.original_price).toLocaleString()).show();
        //                 $('#dynamicDiscount').text(res.discount + '% off').show();

        //                 $('#staticOriginal').hide();
        //                 $('#staticDiscount').hide();

        //                 $('#dynamicImage').attr('src', res.image);

        //                 // Add to cart hidden fields
        //                 $('#variantId').val(res.id);
        //                 $('#variantPrice').val(res.price);
        //                 $('#variantOriginalPrice').val(res.original_price);
        //                 $('#variantDiscount').val(res.discount);

        //                 let fullPath = res.image;
        //                 let filename = fullPath.split('/').pop();
        //                 $('#variantImage').val(filename);

        //                 // Saving amount
        //                 let savingAmount = res.original_price - res.price;
        //                 if (savingAmount > 0) {
        //                     $('#dynamicSaving').text(
        //                         'You will save ₹ ' + Number(savingAmount).toLocaleString());
        //                     $('#saveBox').show();
        //                 } else {
        //                     $('#saveBox').hide();
        //                 }
        //             }
        //         });
        //     }
        // });

        ///// WISHLIST
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
                        showCartMessage('Added to wishlist', false);
                    }

                    if (res.status === "removed") {
                        btn.removeClass("active");
                        showCartMessage('Removed from wishlist', true);
                    }

                    setTimeout(() => {
                        $("#flash-message .alert").fadeOut();
                    }, 2000);

                    $("#wishlistArea").html(res.html);
                    $("#wishlist-count").text(res.count);
                },

                error: function(xhr) {
                    if (xhr.status === 401) {
                        showCartMessage('Please login to use wishlist', true);
                      
                        setTimeout(() => {
                            $("#flash-message .alert").fadeOut();
                        }, 2500);

                        btn.removeClass("active");
                    }
                }
            });
        });

        ///// REMOVE WISHLIST
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
                            $(".cart-count").text(res.count);

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

        $(document).on('click', '.wishlistBtn', function () {

            let btn = $(this);
            let heart = btn.find('.heartPath');
            let productId = btn.data('product-id');
            let redirectUrl = btn.data('redirect');

            $.ajax({
                url: "/wishlist/toggle/" + productId,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {

                    if (res.status === 'added') {
                        heart.attr('fill', 'red');

                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 500);

                        return;
                    }
                    if (res.status === 'already') {
                        heart.attr('fill', 'red');

                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 500);

                        return;
                    }
                }
            });
        });

        ////// ADD TO CART
    
        $(document).on('click', '.addToCartBtn', function(e) {
            
            e.preventDefault();
            let btn = $(this);

            let productId     = btn.data('product-id');
            let variantId     = btn.data('variant-id');
            let price         = btn.data('price');
            let originalPrice = btn.data('original-price');
            let discount      = btn.data('discount');
            let image         = btn.data('image');

            $.ajax({
                url: '/cart/add',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    product_id: productId,
                    variant_id: variantId,
                    price: price,
                    original_price: originalPrice,
                    discount: discount,
                    image: image,
                    quantity: 1
                },
                success: function(res) {
                    if(res.status === 'success'){
                        if(res.cartCount){
                            $('.cart-count').text(res.cartCount);
                        }
                        if(res.cartItems){
                            updateHeaderCart(res.cartItems);
                        }

                        btn.addClass('in-cart');
                        showCartMessage('Product added to cart.', false);
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 401){
                        showCartMessage('Please login to add to cart.', true);
                    } else {
                        showCartMessage('Something went wrong.', true);
                    }
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

                                        <h6 class="dz-price mb-0">₹${itemSubtotal.toFixed(0)}</h6>

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


        ////Remove Cart Item
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
                            if (Number(res.subtotal) <= 0 || res.count === 0) {

                                $("#cart-total-section").html(`
                                    <div class="cart-total text-center">
                                        <h5 class="mb-0 fw-bold">Your cart is empty.</h5>
                                    </div>
                                `);

                                $(".sidebar-cart-list").html(`
                                    <li><p class="text-center fs-5 fw-bold">Your cart is empty.</p></li>
                                `);
                                $("#cart-action-buttons").hide();
                            } else {

                                $("#cart-total-section").html(`
                                    <div class="cart-total d-flex justify-content-between">
                                        <h5 class="mb-0">Subtotal:</h5>
                                        <h5 class="mb-0">₹ ${Number(res.subtotal).toLocaleString()}</h5>
                                    </div>
                                `);
                                $("#cart-action-buttons").show();
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

        // When user clicks star,  STAR RATING
        $("#stars li.star").on("click", function () {
            @if(!auth()->check())
                $("#flash-message").html(`
                    <div class="alert alert-danger">Please login to use rating.</div>
                `);

                setTimeout(() => {
                    $("#flash-message .alert").fadeOut();
                }, 2500);

                return false; 
            @endif

            var value = $(this).data("value");
            $("#ratingInput").val(value);
            $("#stars li.star").removeClass("selected");
            $("#stars li.star").each(function(index){
                if(index < value){
                    $(this).addClass("selected");
                }
            });

            $("#ratingError").text("");
        });

        //  STAR RATING AJAX submit
        $("#reviewForm").submit(function(e){
            e.preventDefault();

            $("#ratingError").text("");
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('product.review.store', $product->id) }}",
                method: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

                success: function(res){
                    $("#comment").html(res.reviews_html);
                    $("#reviewForm")[0].reset(); 
                    $("#stars li.star").removeClass('selected');

                    $("#flash-message").html(`
                        <div class="alert alert-success">Thank you for submitting the review.</div>
                    `);

                    setTimeout(() => {
                        $("#flash-message .alert").fadeOut();
                    }, 2500);

                    location.reload();
                },

                error: function(xhr){
                    let errors = xhr.responseJSON.errors;

                    if (errors.rating) {
                        $("#ratingError").text(errors.rating[0]);
                    }
                }
            });
        });

        $(document).ready(function() {
            setTimeout(function() {
                $('#reviewAlert').fadeOut('slow'); 
            }, 5000); 
        });


    </script>
@endsection
