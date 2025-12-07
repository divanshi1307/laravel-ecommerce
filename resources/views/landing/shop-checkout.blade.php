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
                                <label class="label-title">State</label>

                                @if($location)
                                    <input type="hidden" name="state" value="{{ $location->state }}">
                                    <input type="text" class="form-control" value="{{ $location->state }}" disabled>
                                @else
                                    <input type="text" name="state" class="form-control" value="">
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

                            @forelse($cartItems as $item)
                                <div class="cart-item style-1">
                                    <div class="dz-media">
                                            <img src="{{ $item->product && $item->product->first_image_url ? asset('uploads/products/' . $item->product->first_image_url) : asset('images/default-product.png') }}" alt="{{ $item->product->title ?? 'Product Image' }}" >
                                    </div>
                                    <div class="dz-content">
                                        <h6 class="title mb-0">{{ $item->product->title }}</h6>

                                        @php
                                            $product = $item->product;
                                            $gstPercentage = 0;
                                            if ($product->gst) {
                                                $gstPercentage = (float) str_replace('%', '', $product->gst->gst_percentage);
                                            }

                                            if ($product->product_type == 'simple') {
                                                $basePrice = $product->price ?? 0;
                                                $PriceWithGst = $basePrice + ($basePrice * $gstPercentage / 100);

                                            } else {
                                                $PriceWithGst = $item->price;
                                            }

                                        @endphp

                                        <span class="price">
                                            ₹{{ number_format($PriceWithGst, 0) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p>No items in cart.</p>
                            @endforelse

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

                            <table>
                                <tbody>
                                    <tr class="total">
                                        <td>Total</td>
                                        <td class="price">₹{{ number_format($grandTotal, 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="accordion dz-accordion accordion-sm" id="accordionFaq1">
                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading1">
                                        <div class="accordion-button collapsed custom-control custom-checkbox border-0" data-bs-toggle="collapse" data-bs-target="#collapse1" role="navigation"  aria-expanded="true" aria-controls="collapse1">
                                            <input class="form-check-input radio payment-radio" type="radio" name="payment_method" value="bank_transfer" id="flexRadioDefault3">
                                            <label class="form-check-label" for="flexRadioDefault3">
                                                Direct bank transfer
                                            </label>
                                        </div>
                                    </div>
                                    <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#accordionFaq1">
                                        <div class="accordion-body">
                                            <p class="m-b0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading2">
                                        <div class="accordion-button collapsed custom-control custom-checkbox border-0" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse2" role="navigation" 
                                            aria-expanded="true" aria-controls="collapse2">

                                            <input class="form-check-input radio payment-radio" type="radio" 
                                                name="payment_method" value="cod" id="payment_cod" 
                                                {{ old('payment_method') == 'cod' ? 'checked' : '' }}>

                                            <label class="form-check-label" for="payment_cod">
                                                Cash on delivery
                                            </label>
                                        </div>
                                    </div>

                                    @error('payment_method')
                                        <div class="text-danger ps-3 mt-1">{{ $message }}</div>
                                    @enderror

                                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionFaq1">
                                        <div class="accordion-body">
                                            <p class="m-b0">Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <div class="accordion-header" id="heading3">
                                        <div class="accordion-button collapsed custom-control custom-checkbox border-0" data-bs-toggle="collapse" data-bs-target="#collapse3" role="navigation" aria-expanded="true" aria-controls="collapse3">
                                            <input class="form-check-input radio payment-radio" type="radio" name="payment_method" value="paypal" id="flexRadioDefault4">
                                            <label class="form-check-label" for="flexRadioDefault4">
                                                Paypal
                                            </label>
                                            <img src="images/shop/payment.jpg" alt="/">
                                            <a href="javascript:void(0);">What is PayPal?</a>
                                        </div>
                                    </div>
                                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#accordionFaq1">
                                        <div class="accordion-body">
                                            <p class="m-b0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="text">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="javascript:void(0);">privacy policy.</a></p>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox d-flex m-b15">
                                    <input type="checkbox" class="form-check-input" id="basic_checkbox_3">
                                    <label class="form-check-label" for="basic_checkbox_3">I have read and agree to the website terms and conditions </label>
                                </div>
                            </div>
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
            $.ajax({
                url: "{{ route('wishlist.render') }}",
                type: "GET",
                success: function (html) {
                    $("#wishlistArea").html(html);
                }
            });

            $(document).on("change", ".payment-radio", function () {
                let selected = $(this).val();
                $("#payment_method_input").val(selected);
            });
        });
    </script>
@endsection
