@extends('landing.layout')

@section('content')
<div class="page-wraper">
    <div class="page-content bg-light">

        <!-- Banner Start -->
        <div class="dz-bnr-inr bg-secondary overlay-black-light" style="background-image:url(images/background/bg1.jpg);">
            <div class="container">
                <div class="dz-bnr-inr-entry">
                    <h1>Shop Checkout</h1>
                    <nav aria-label="breadcrumb" class="breadcrumb-row">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item">Shop Checkout</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Banner End -->

        <div class="content-inner-1">
            <div class="container">
                <div class="row shop-checkout">

                    <!-- Left Section -->
                    <div class="col-xl-8">
                        <h4 class="title m-b15">Billing details</h4>

                        <div class="accordion dz-accordion accordion-sm" id="accordionFaq">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <a href="{{ url('/login') }}" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                        Returning customer? Click here to login
                                        <span class="toggle-close"></span>
                                    </a>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                                    <div class="accordion-body">
                                        <p>If your order has not yet shipped, you can contact us to change your shipping address.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form class="row" method="POST" action="{{ route('checkout.store') }}">
                            @csrf
                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="form-group m-b25">
                                    <label class="label-title">First Name<span class="text-danger">*</span></label>
                                    <input name="first_name" value="{{ old('first_name') }}"
                                        class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group m-b25">
                                    <label class="label-title">Last Name</label>
                                    <input name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Last Name">
                                    
                                </div>
                            </div>

                            <!-- Company -->
                            <div class="col-md-12">
                                <div class="form-group m-b25">
                                    <label class="label-title">Company Name (optional)</label>
                                    <input name="company_name" value="{{ old('company_name') }}" class="form-control" placeholder="Company Name">
                                </div>
                            </div>

                            <!-- Country (always India + disabled) -->
                            <div class="col-md-12">
                                <div class="m-b25">
                                    <label class="label-title">Country / Region</label>
                                    <input type="hidden" name="country" value="India">
                                    <select class="form-select w-100" disabled>
                                        <option selected>India</option>
                                    </select>
                                </div>
                            </div>

                            <!-- State -->
                            <div class="m-b25">
                                <label class="label-title">State</label>

                                @if($location)
                                    <input type="hidden" name="state" value="{{ $location->state }}">
                                    <input type="text" class="form-control" value="{{ $location->state }}" disabled>
                                @else
                                    <input type="text" name="state" class="form-control" value="">
                                @endif
                            </div>

                            <!-- City -->
                            <div class="m-b25">
                                <label class="label-title">Town / City</label>

                                @if($location)
                                    <!-- Disabled INPUT + hidden VALUE -->
                                    <input type="hidden" name="city" value="{{ $location->city }}">
                                    <input type="text" class="form-control" value="{{ $location->city }}" disabled>
                                @else
                                    <!-- Editable input -->
                                    <input type="text" name="city" class="form-control" value="">
                                @endif
                            </div>

                            <!-- State -->
                            <div class="m-b25">
                                <label class="label-title">Area</label>

                                @if($location)
                                    <input type="hidden" name="area" value="{{ $location->area }}">
                                    <input type="text" class="form-control" value="{{ $location->area }}" disabled>
                                @else
                                    <input type="text" name="area" class="form-control" value="">
                                @endif
                            </div>

                            <!-- ZIP / PINCODE -->
                            <div class="form-group m-b25">
                                <label class="label-title">ZIP Code</label>

                                @if($savedPincode)
                                    <input type="hidden" name="pincode" value="{{ $savedPincode }}">
                                    <input type="text" class="form-control" value="{{ $savedPincode }}" disabled>
                                @else
                                    <input type="text" name="pincode" id="pincode" class="form-control">
                                @endif
                            </div>

                            <!-- Street Address -->
                            <div class="col-md-12">
                                <div class="form-group m-b25">
                                    <label class="label-title">Street Address <span class="text-danger">*</span></label>

                                    <!-- Street Address -->
                                    <input name="street_address" value="{{ old('street_address') }}" class="form-control m-b15 @error('street_address') is-invalid @enderror" placeholder="House number and street name">

                                    @error('street_address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <!-- Apartment -->
                                    <input name="apartment" value="{{ old('apartment') }}" class="form-control" placeholder="Apartment, suite, unit (optional)">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-12">
                                <div class="form-group m-b25">
                                    <label class="label-title">Phone<span class="text-danger">*</span></label>
                                    <input name="phone" value="{{ old('phone') }}"
                                        class="form-control @error('phone') is-invalid @enderror">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-12">
                                <div class="form-group m-b25">
                                    <label class="label-title">Email address<span class="text-danger">*</span></label>
                                    <input name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12 m-b25">
                                <div class="form-group">
                                    <label class="label-title">Order notes (optional)</label>
                                    <textarea name="order_notes" class="form-control" rows="5" placeholder="Notes about your order">{{ old('order_notes') }}</textarea>
                                </div>
                            </div>

                            <input type="hidden" name="payment_method" id="payment_method_input">

                            <!-- SUBMIT BUTTON INSIDE FORM -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-secondary float-end">PLACE ORDER</button>
                            </div>

                        </form>
                    </div>

                    <!-- Right Section -->

                    <div class="col-xl-4 side-bar">
                        <h4 class="title m-b15">Your Order</h4>

                        <div class="order-detail sticky-top">

                            {{-- CART ITEMS --}}
                            @forelse($cartItems as $item)
                                <div class="cart-item style-1">
                                    <div class="dz-media">
                                        <img src="{{ $item->product && $item->product->first_image_url 
                                            ? asset('uploads/products/' . $item->product->first_image_url) 
                                            : asset('images/default-product.png') }}"
                                            alt="{{ $item->product->title ?? 'Product Image' }}">
                                    </div>

                                    <div class="dz-content">
                                        <h6 class="title mb-0">{{ $item->product->title }}</h6>

                                        @php
                                            $product = $item->product;
                                            $gstPercentage = $product->gst ? (float) str_replace('%', '', $product->gst->gst_percentage) : 0;

                                            if ($product->product_type == 'simple') {
                                                $basePrice = $product->price ?? 0;
                                                $priceWithGst = $basePrice + ($basePrice * $gstPercentage / 100);
                                                $finalPrice = $priceWithGst * $item->quantity;
                                            } else {
                                                $priceWithGst = $item->price;
                                                $finalPrice = $priceWithGst * $item->quantity;
                                            }
                                        @endphp

                                        <span class="price">
                                            ₹{{ number_format($finalPrice, 0) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p>No items in cart.</p>
                            @endforelse

                            {{-- --------------------------
                                PRICE CALCULATION
                            --------------------------- --}}
                            @php
                                $subtotal = 0;

                                foreach ($cartItems as $item) {
                                    $basePrice = $item->price;
                                    $subtotal += $basePrice * $item->quantity;
                                }

                                $couponCode = session('coupon_code');
                                $discountAmount = session('discount') ?? 0;

                                $finalTotal = $subtotal + $shippingCharge - $discountAmount;
                                if ($finalTotal < 0) $finalTotal = 0;
                            @endphp

                            {{-- --------------------------
                                TOTAL SECTION
                            --------------------------- --}}
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="price">₹{{ number_format($subtotal, 0) }}</td>
                                    </tr>
                                    {{-- If coupon applied --}}
                                    @if($couponCode)
                                        <tr>
                                            <td><strong>Coupon ({{ $couponCode }})</strong></td>
                                            <td class="price text-success">- ₹{{ number_format($discountAmount, 0) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td>Shipping Charge</td>
                                        <td class="price text-success">₹{{ number_format($shippingCharge, 2) }}</td>
                                    </tr>

                                    <tr class="total">
                                        <td><strong>Total</strong></td>
                                        <td class="price">₹{{ number_format($finalTotal, 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            {{-- --------------------------
                                PAYMENT METHODS
                            --------------------------- --}}
                            <div class="accordion dz-accordion accordion-sm" id="accordionFaq1">
                                
                                {{-- Bank Transfer --}}
                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading1">
                                        <div class="accordion-button collapsed custom-control custom-checkbox border-0"
                                            data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true">

                                            <input class="form-check-input radio payment-radio"
                                                type="radio" name="payment_method" value="bank_transfer" id="pm_bank">

                                            <label class="form-check-label" for="pm_bank">
                                                Direct bank transfer
                                            </label>
                                        </div>
                                    </div>

                                    <div id="collapse1" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            Make your payment directly into our bank account.
                                        </div>
                                    </div>
                                </div>

                                {{-- Cash on Delivery --}}
                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading2">
                                        <div class="accordion-button collapsed custom-control custom-checkbox border-0"
                                            data-bs-toggle="collapse" data-bs-target="#collapse2">

                                            <input class="form-check-input radio payment-radio"
                                                type="radio" name="payment_method" value="cod" id="pm_cod"
                                                {{ old('payment_method') == 'cod' ? 'checked' : '' }}>

                                            <label class="form-check-label" for="pm_cod">
                                                Cash on delivery
                                            </label>
                                        </div>
                                    </div>

                                    @error('payment_method')
                                        <div class="text-danger ps-3 mt-1">{{ $message }}</div>
                                    @enderror

                                    <div id="collapse2" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            Your order will be paid in cash upon delivery.
                                        </div>
                                    </div>
                                </div>

                                {{-- PayPal --}}
                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading3">
                                        <div class="accordion-button collapsed custom-control custom-checkbox border-0"
                                            data-bs-toggle="collapse" data-bs-target="#collapse3">

                                            <input class="form-check-input radio payment-radio"
                                                type="radio" name="payment_method" value="paypal" id="pm_paypal">

                                            <label class="form-check-label" for="pm_paypal">Paypal</label>
                                            <img src="images/shop/payment.jpg" alt="">
                                        </div>
                                    </div>

                                    <div id="collapse3" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            Pay securely with PayPal.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text">
                                Your personal data will be used to process your order and support your experience.
                            </p>
                            {{-- <div class="form-group">
                                <div class="custom-control custom-checkbox d-flex m-b15">
                                    <input type="checkbox" class="form-check-input" id="terms_check">
                                    <label class="form-check-label" for="terms_check">
                                        I agree to the website terms and conditions
                                    </label>
                                </div>
                            </div> --}}
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

            $(document).on("change", ".payment-radio", function () {
                let selected = $(this).val();
                $("#payment_method_input").val(selected);
            });

            //// REMOVE CART FROM SIDEBAR
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
        });
    </script>
@endsection
