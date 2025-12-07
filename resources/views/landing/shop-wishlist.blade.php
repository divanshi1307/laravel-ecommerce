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
                                        <th>Stock</th>
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
                                                    $minPrice = ($product->product_type == 'variant')
                                                        ? $product->variants->min('variant_price')
                                                        : null;
                                                @endphp

                                                <h5 class="price" style="margin:0; font-size:22px; font-weight:600; white-space:nowrap;">
                                                    @if($product->product_type == 'simple')
                                                        ₹&nbsp;{{ number_format($product->price, 0) }}
                                                    @else
                                                        ₹&nbsp;{{ number_format($minPrice ?? 0, 0) }}
                                                    @endif
                                                </h5>
                                            </td>
                                            
                                            <td class="product-item-stock">
                                                @if($item->product->product_type === 'simple')
                                                    <span class="{{ $item->product->stock_status ? 'text-success' : 'text-danger' }}">
                                                        {{ $item->product->stock_status ? 'In Stock' : 'Out of Stock' }}
                                                    </span>
                                                @else
                                                    <span class="text-primary">Out of Stock</span>
                                                @endif
                                            </td>

                                            <!-- Add to Cart -->
                                            <td class="product-item-totle"><a href="shop-cart.html" class="btn btn-secondary btnhover text-nowrap">Add To Cart</a></td>

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

        $(document).ready(function() {
            $.ajax({
                url: "{{ route('wishlist.render') }}",
                type: "GET",
                success: function(html) {
                    $("#wishlistArea").html(html);
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
                            setTimeout(() => {
                                location.reload();
                            }, 1000);

                        });

                        let count = $("#wishlistArea li").length;
                        $("#wishlist-count").text(count);

                        if (count === 0) {
                            $("#wishlistArea").html(`
                                <li><p class="text-center fs-5 fw-bold">No items in wishlist.</p></li>
                            `);
                        }

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

    </script>
@endsection
