	
@extends('landing.layout')

@section('content')

    <div class="page-wraper">

        <!-- Slider -->
		<div class="page-content bg-light">
			<div class="d-sm-flex justify-content-between container-fluid py-3">
		</div>

        <div class="content-inner py-0 overflow-hidden">
            <div class="swiper-pagination-two"></div>
            <div class="container-fluid">
                <div class="swiper portfolio-gallery3">
                    <div class="swiper-wrapper">

                        @foreach($sliderImages as $slide)
                            @if($slide->photoUpload) 
                                <div class="swiper-slide">
                                    <div class="portfolio-box style-1">
                                        <div class="dz-media">
                                            <img src="{{ asset('uploads/sliders/' .$slide->photoUpload->file_name) }}" alt="slider image">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Category and Subcategory-->
        @foreach ($categories as $category)
            <section class="content-inner">
                <div class="container">	
                    <div class="section-head style-1 wow fadeInUp d-flex justify-content-between" data-wow-delay="0.2s">
                        <div class="left-content">
                            <h2 class="title">{{ $category->category_name }}</h2>
                        </div>

                        {{-- <a href="{{ url('/category/'.$category->id) }}" 
                        class="text-secondary font-14 d-flex align-items-center gap-1">
                            See All
                            <i class="icon feather icon-chevron-right font-18"></i>
                        </a>			 --}}
                    </div>

                    <div class="row g-3" data-masonry='{"percentPosition": true}'>

                        @foreach ($category->children as $sub)
                            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-1">
                                <div class="dz-card bg-white shadow-sm rounded overflow-hidden h-100">
                                    <div class="dz-media ratio ratio-1x1">
                                        <a href="{{ url('/subcategory/'.$sub->id) }}">
                                            <img src="{{ asset('storage/' .$sub->subcategory_image_small) }}" 
                                                alt="{{ $sub->category_name }}" 
                                                class="img-fluid object-fit-cover">
                                        </a>
                                    </div>

                                    <div class="p-3">
                                        <p class="mb-0 text-center fw-medium">{{ $sub->category_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($category->children->count() == 0)
                            <p class="text-muted">No subcategories found.</p>
                        @endif
                    </div>
                </div>

                <div class="container"><br>
                    <div class="site-footer style-1"></div>
                </div>
            </section>
        @endforeach

        <!-- Most popular products -->	
		<section class="content-inner">
			<div class="container">
				<div class=" row justify-content-md-between align-items-start">
					<div class="col-lg-6 col-md-12">
						<div class="section-head style-1 m-b30  wow fadeInUp" data-wow-delay="0.2s">
							<div class="left-content">
								<h2 class="title">Most popular products</h2>
							</div>
						</div>	
					</div>
				</div>
				<div class="clearfix">
					<ul id="masonry" class="row g-xl-4 g-3">
                        @foreach ($products as $product)
                            <li class="card-container col-6 col-xl-3 col-lg-3 col-md-4 col-sm-6 wow fadeInUp">
                                <div class="shop-card">
                                    <div class="dz-media">
                                        <img src="{{ asset('uploads/products/' .$product->first_image_url) }}" alt="{{ $product->title }}">
                                        <div class="shop-meta">
                                            {{-- <a href="javascript:void(0);" class="btn btn-secondary btn-md btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                <span class="d-md-block d-none">Quick View</span>
                                            </a> --}}

                                            <a href="{{ route('product.show', $product->id) }}" 
                                            class="btn btn-secondary btn-md btn-rounded">
                                                <i class="fa-solid fa-eye d-md-none d-block"></i>
                                                <span class="d-md-block d-none">Quick View</span>
                                            </a>

                                            <div class="btn btn-primary meta-icon dz-wishicon">
                                                <i class="icon feather icon-heart dz-heart"></i>
                                                <i class="icon feather icon-heart-on dz-heart-fill"></i>
                                            </div>
                                            <div class="btn btn-primary meta-icon dz-carticon">
                                                <i class="flaticon flaticon-basket"></i>
                                                <i class="flaticon flaticon-basket-on dz-heart-fill"></i>
                                            </div>
                                        </div>	
                                    </div>

                                    <div class="dz-content">
                                        <h5 class="title">
                                            <a href="{{ url('product/'.$product->id) }}">
                                                {{ \Illuminate\Support\Str::limit($product->title, 30, '...') }}
                                            </a>
                                        </h5>

                                        {{-- Show Price --}}
                                        @if ($product->product_type == 'simple')
                                            <h5 class="price">₹ {{ number_format($product->price, 0) }}</h5>

                                        @elseif ($product->product_type == 'variant')

                                            @php
                                                if (!empty($product->variant_price)) {
                                                    $prices = is_array($product->variant_price)
                                                        ? $product->variant_price
                                                        : json_decode($product->variant_price, true);

                                                    $minPrice = min($prices);
                                                }

                                                if (isset($product->variants) && $product->variants->count() > 0) {
                                                    $minPrice = $product->variants->min('variant_price');
                                                }
                                            @endphp
                                            <h5 class="price">₹ {{ number_format($minPrice, 0) }}</h5>
                                        @endif
                                    </div>

                                    @if ($product->special_price)
                                        <div class="product-tag">
                                            <span class="badge">Special Price</span>
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
				</div>
			</div>
		</section>

        <!-- Brands -->	
        <section class="content-inner-2">
            <div class="container">	
                <div class="section-head style-1 wow fadeInUp d-flex justify-content-between" data-wow-delay="0.2s">
                    <div class="left-content">
                        <h2 class="title">Browse By Brands</h2>
                    </div>
                    <a href="{{ url('brands') }}" class="text-secondary font-14 d-flex align-items-center gap-1">
                        See All 
                        <i class="icon feather icon-chevron-right font-18"></i>
                    </a>			
                </div>

                <div class="swiper swiper-company">
                    <div class="swiper-wrapper">
                        @foreach($brands as $brand)
                        <div class="swiper-slide">
                            <div class="company-box style-1 wow fadeInUp" data-wow-delay="0.4s">
                                <div class="dz-media">
                                    <img src="{{ asset('storage/'.$brand->brand_logo) }}" alt="{{ $brand->brand_name }}" class="company-img">
                                    <img src="{{ asset('storage/'.$brand->brand_logo) }}" alt="{{ $brand->brand_name }}" class="logo">
                                </div>
                                <div class="dz-content">
                                    <h6 class="title">{{ $brand->brand_name }}</h6>
                                </div>		
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
