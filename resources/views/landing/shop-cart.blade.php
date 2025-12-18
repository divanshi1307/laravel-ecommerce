@extends('landing.layout')

@section('content')
<div class="page-wraper">
    <div class="page-content bg-light">
		<!--Banner Start-->
		<div class="dz-bnr-inr bg-secondary overlay-black-light" style="background-image:url(images/background/bg1.jpg);">
			<div class="container">
				<div class="dz-bnr-inr-entry">
					<h1>Shop Cart</h1>
					<nav aria-label="breadcrumb" class="breadcrumb-row">
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{"/"}}"> Home</a></li>
							<li class="breadcrumb-item">Shop Cart</li>
						</ul>
					</nav>
				</div>
			</div>	
		</div>
		<!--Banner End-->
		
		<!-- contact area -->
		<section class="content-inner shop-account">
			<!-- Product -->
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<div class="table-responsive">
							<table class="table check-tbl">
								<thead>
									<tr>
										<th>Product</th>
										<th></th>
										<th>Price</th>
										<th>Quantity</th>
										<th>Subtotal</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    @if($cartItems->count() == 0)
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <strong style="font-size: 18px;">Your cart is empty.</strong>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($cartItems as $item)
                                            <tr>
                                                <!-- PRODUCT IMAGE -->
                                                <td class="product-item-img">
                                            
                                                   <img src="{{ $item->product && $item->product->first_image_url ? asset('uploads/products/' . $item->product->first_image_url) : asset('images/default-product.png') }}" alt="{{ $item->product->title ?? 'Product Image' }}" >

                                                </td>

                                                <!-- PRODUCT NAME -->
                                                <td class="product-item-name">
                                                    {{ $item->product->name }}

                                                    @if($item->variant_id)
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $item->product->name }}
                                                        </small>
                                                    @endif
                                                </td>

                                                <!-- PRODUCT PRICE -->
                                                <td class="product-item-price">
                                                    @php
                                                        $product = $item->product;

                                                        // Initialize
                                                        $priceWithGst = 0;
                                                        $gstPercentage = 0;
                                                        $originalWithGst = null;
                                                        $discount = null;
                                                        $grandTotal = 0;

                                                        if ($product->product_type == 'simple') {
                                                            $priceWithGst = $item->price ?? 0;
                                                            $originalWithGst = $item->original_price ?? null;
                                                        } else {
                                                            $gstPercentage = $product->gst ? (float) str_replace('%','',$product->gst->gst_percentage) : 0;

                                                            $variantPrice = $item->price ?? 0;
                                                            $variantOriginal = $item->original_price ?? 0;

                                                            $priceWithGst = $variantPrice;
                                                            $originalWithGst = $variantOriginal ;
                                                        }

                                                        if ($originalWithGst && $originalWithGst > $priceWithGst) {
                                                            $discount = round((($originalWithGst - $priceWithGst) / $originalWithGst) * 100);
                                                        }

                                                        $subtotal = $priceWithGst * $item->quantity;
                                                        $grandTotal += $subtotal;
                                                    @endphp

                                                    {{-- FINAL PRICE OUTPUT --}}
                                                    <div>
                                                        <strong>₹ {{ number_format($priceWithGst, 0) }}</strong>
                                                    </div>

                                                    @if($originalWithGst)
                                                        <small style="text-decoration: line-through; color:#888;">
                                                            ₹ {{ number_format($originalWithGst, 0) }}
                                                        </small>
                                                    @endif

                                                    @if($discount)
                                                        <span style="color:green; font-weight:600;">
                                                            {{ $discount }}% Off
                                                        </span>
                                                    @endif
                                                </td>

                                                <!-- QUANTITY -->
                                                <td class="product-item-quantity">
                                                    <div class="quantity btn-quantity style-1 me-3">
                                                        <input type="text" value="{{ $item->quantity }}" min="1" class="form-control quantity-input" data-id="{{ $item->id }}">
                                                    </div>
                                                </td>

                                                <!-- SUBTOTAL -->
                                                <td class="product-item-subtotal" id="subtotalValue">
                                                    ₹ {{ number_format($subtotal) }}
                                                </td>

                                                <!-- REMOVE BUTTON -->
                                                <td class="product-item-close">
                                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="btn btn-link p-0">
                                                            <i class="ti-close text-danger"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
							</table>
						</div>
                        <form action="{{ route('cart.applyCoupon') }}" method="POST">
                            @csrf
                            <div class="row shop-form m-t30">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group mb-0">
                                            <input name="coupon_code" required type="text" class="form-control" placeholder="Coupon Code">
                                            <div class="input-group-addon">
                                                <button type="submit" class="btn coupon">
                                                    Apply Coupon
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill float-end">Continue Shopping</a>
                                </div>
                            </div>
                        </form>
					</div>
                    @if($cartItems->count() > 0)
                        <div class="col-lg-4 price-detail-section">
                            {{-- <h4 class="title mb15">Cart Total</h4> --}}
                            <h4 class="title mb15">Price details</h4>
                            <div class="cart-detail">
                                {{-- <a href="javascript:void(0);" class="btn btn-outline-secondary w-100 m-b20">Bank Offer 5% Cashback</a>
                                <div class="icon-bx-wraper style-4 m-b15">
                                    <div class="icon-bx">
                                        <i class="flaticon flaticon-ship"></i>
                                    </div>
                                    <div class="icon-content">
                                        <span class=" font-14">FREE</span>
                                        <h6 class="dz-title">Enjoy The Product</h6>
                                    </div>
                                </div>
                                <div class="icon-bx-wraper style-4 m-b30">
                                    <div class="icon-bx">
                                        <img src="images/shop/shop-cart/icon-box/pic2.png" alt="/">
                                    </div>
                                    <div class="icon-content">
                                        <h6 class="dz-title">Enjoy The Product</h6>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting</p>
                                    </div>
                                </div> --}}

                                @php
                                    $totalMrp = 0;
                                    $totalSelling = 0;
                                    $totalSaving = 0;
                                @endphp

                                @foreach($cartItems as $item)
                                    @php
                                        $mrp = ($item->original_price && $item->original_price > 0)
                                                ? $item->original_price
                                                : $item->price;

                                        $selling = $item->price;

                                        $totalMrp += $mrp * $item->quantity;
                                        $totalSelling += $selling * $item->quantity;
                                    @endphp
                                @endforeach

                                @php
                                    $totalDiscount = max(0, $totalMrp - $totalSelling);
                                @endphp

                                <div class="cart-summary-box mb-20">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold small text-secondary">Price ({{ $cartItems->count() }} items)</span>
                                        <span>₹{{ number_format($totalMrp) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold small text-secondary">Discount</span>
                                        <span class="text-success">- ₹{{ number_format($totalDiscount) }}</span>
                                    </div>

                                    <hr>
                                </div>
                                
                                @foreach($cartItems as $item)
                                    @php
                                        if (!empty($item->original_price) && $item->original_price > $item->price) {
                                            $saving = ($item->original_price - $item->price) * $item->quantity;
                                        } else {
                                            $saving = 0;
                                        }

                                        $totalSaving += $saving;
                                    @endphp
                                @endforeach

                                @if($totalSaving > 0)
                                    <div class="save-text">
                                        <i class="icon feather icon-check-circle"></i>
                                        <span class="m-l10">
                                            You will save ₹{{ number_format($totalSaving) }} on this order
                                        </span>
                                    </div>
                                @endif

                                <table>
                                    <tbody>

                                        @php
                                            $grandTotal = 0;
                                        @endphp

                                        @foreach($cartItems as $item)
                                            @php
                                                $product = $item->product;
                                                // ---------- PRICE LOGIC ----------
                                                if ($product->product_type == 'simple') {
                                                    $basePrice = $item->price;
                                                    $baseOriginal = $item->original_price;
                                                } else {
                                                    $basePrice = $item->price ?? 0;
                                                    $baseOriginal = $item->original_price ?? null;
                                                }
                                                $subtotal = $basePrice * $item->quantity;
                                                $grandTotal += $subtotal;

                                            @endphp
                                        @endforeach

                                        <tr class="total">
                                            <td>
                                                <h5 class="mb-0">Total</h5>
                                            </td>
                                            <td class="price" id="grandTotalValue">
                                                ₹ {{ number_format($grandTotal, 0) }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                                <a href="{{route('checkout')}}" class="btn btn-secondary w-100">PLACE ORDER</a>
                            </div>
                        </div>
                    @endauth
				</div>
			</div>
			<!-- Product END -->
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

        ////REMOVE CART ITEM
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

        //// CART QUANTITY UPDATE 
        $(document).on('change', '.quantity-input', function() {
            let quantity = $(this).val();
            let cartItemId = $(this).data('id');

            if (quantity < 1) quantity = 1;
            let row = $(this).closest("tr"); 

            $.ajax({
                url: '/cart/update/' + cartItemId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: quantity
                },
                success: function(res) {
                    row.find(".product-item-subtotal").text("₹ " + res.itemTotal);
                    $("#subtotalValue").text("₹ " + res.subtotal);
                    $("#grandTotalValue").text("₹ " + res.grandTotal);

                },
                error: function() {
                    alert('Error updating quantity');
                }
            });
        });
    </script>
@endsection
