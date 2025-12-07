	
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
                            <div id="flash-message"></div>
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

@section('script')
    <script>

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

        ////// ADD TO CART
        $(document).on('click', '.addToCartBtn', function(e){
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
                error: function(xhr) {
                    if (xhr.status === 401) {
                        $("#flash-message").html(`
                            <div class="alert alert-danger">Please login to use add cart.</div>
                        `);

                        setTimeout(() => {
                            $("#flash-message .alert").fadeOut();
                        }, 2500);

                        btn.removeClass("active");
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