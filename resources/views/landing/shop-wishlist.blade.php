@extends('landing.layout')

@section('content')
<div class="page-wraper">

    <div class="page-content bg-light">
        <!--Banner Start-->
        <div class="dz-bnr-inr bg-secondary overlay-black-light" style="background-image:url(images/background/bg1.jpg);">
            <div class="container">
                <div class="dz-bnr-inr-entry">
                    <h1>Wishlist</h1>
                    <nav aria-label="breadcrumb" class="breadcrumb-row">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"> Home</a></li>
                            <li class="breadcrumb-item">Wishlist</li>
                        </ul>
                    </nav>
                </div>
            </div>	
        </div>
        <!--Banner End-->

        <div class="content-inner-1">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="table-responsive">
                            <div id="flash-message"></div>
                            <table class="table check-tbl style-1">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th></th>
                                        <th>Price</th>
                                        {{-- <th>Stock</th> --}}
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="wishlistTableBody">
                                    @forelse($wishlist as $item)
                                        <tr>
                                            <td class="product-item-img">
                                                <img src="{{ asset('uploads/products/' . $item->product->first_image_url) }}" width="80" alt="">
                                            </td>

                                            <td class="product-item-name">
                                                {{ $item->product->title }}
                                            </td>

                                            <td class="product-item-price">
                                                @php
                                                    $product = $item->product;
                                                    $gstPercentage = 0;
                                                    if ($product->gst) {
                                                        $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                                                    }

                                                    // SIMPLE PRODUCT
                                                    if ($product->product_type == 'simple') {
                                                        $basePrice = $product->price ?? 0;
                                                        $finalPrice = $basePrice + ($basePrice * $gstPercentage / 100);
                                                    }

                                                    // VARIANT OR ADULT PRODUCT
                                                    elseif ($product->product_type == 'variant' || $product->product_type == 'adult') {
                                                        $minAttributePrice = $product->attributeRelations->min('price');

                                                        if ($minAttributePrice) {
                                                            $finalPrice = $minAttributePrice + ($minAttributePrice * $gstPercentage / 100);
                                                        } else {
                                                            $finalPrice = 0;
                                                        }
                                                    }
                                                @endphp

                                                <h5 class="price" style="margin:0; font-size:22px; font-weight:600; white-space:nowrap;">
                                                    ₹ {{ number_format($finalPrice, 0) }}
                                                </h5>
                                            </td>

                                            {{-- <td class="product-item-stock">
                                                @if($item->product->product_type === 'simple')
                                                    <span class="{{ $item->product->stock_status ? 'text-success' : 'text-danger' }}">
                                                        {{ $item->product->stock_status ? 'In Stock' : 'Out of Stock' }}
                                                    </span>
                                                @else
                                                    <span class="text-primary">Out of Stock</span>
                                                @endif
                                            </td> --}}

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

                                            <td class="product-item-totle">
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="variant_id" value="{{ $defaultVariant->id ?? '' }}">
                                                    <input type="hidden" name="price" value="{{ $finalPrice }}">
                                                    <input type="hidden" name="original_price" value="{{ $originalBase }}">
                                                    <input type="hidden" name="discount" value="{{ $defaultVariant->discount ?? 0 }}">
                                                    <input type="hidden" name="image" value="{{ $defaultImage }}">
                                                    <input type="hidden" name="source" value="cart-table">

                                                    <button type="submit" class="btn btn-secondary btnhover text-nowrap">
                                                        Add To Cart
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Remove Button -->
                                            <td class="product-item-close">
                                                <a href="javascript:void(0);" class="remove-wishpage" data-id="{{ $item->product->id }}">
                                                    <i class="ti-close"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <p class="fs-4 fw-bold my-3">Your wishlist is empty.</p>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        
        $(document).on("click", ".remove-wishpage", function () {
            let btn = $(this);
            let productId = btn.data("id");

            $.ajax({
                url: "/wishlist/remove/" + productId,
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },

                success: function (res) {
                    if (res.status === "removed") {
                        btn.closest("tr").fadeOut(300, function() {
                            $(this).remove();

                            $(`.dz-wishicon[data-product-id="${productId}"]`).removeClass("active");

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        });

                        let count = $("#wishlistTableBody tr").length;

                        if (count === 0) {
                            $("#wishlistTableBody").html(`
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="fs-4 fw-bold my-3">Your wishlist is empty.</p>
                                    </td>
                                </tr>
                            `);
                        }

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

    </script>
@endsection
