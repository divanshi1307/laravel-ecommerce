@extends('landing.layout')

@section('content')

<div class="page-wraper">
    <div class="page-content bg-light">
        <section class="px-3">
            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 start-side-content">
                    <div class="dz-bnr-inr-entry">
                        <h1>Login</h1>
                        <nav aria-label="breadcrumb text-align-start" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"> Home</a></li>
                                <li class="breadcrumb-item">Login</li>
                            </ul>
                        </nav>	
                    </div>

                    <div class="registration-media">
                        <img src="{{ asset('landing/images/registration/pic3.png')}}" alt="register">
                    </div>
                </div>

                <div class="col-xxl-6 col-xl-6 col-lg-6 end-side-content justify-content-center">
                    <div class="login-area">
                        <h2 class="text-secondary text-center">Login</h2>
                        <p class="text-center m-b25">Welcome, please login to your account</p>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            {{-- Email --}}
                            <div class="m-b30">
                                <label class="label-title">Email Address<span class="text-danger">*</span></label>
                                <input name="email" value="{{ old('email') }}" 
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email Address" type="email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="m-b15">
                                <label class="label-title">Password<span class="text-danger">*</span></label>
                                <div class="secure-input">
                                    <input type="password" name="password" 
                                        class="form-control dz-password @error('password') is-invalid @enderror"
                                        placeholder="Password">
                                    <div class="show-pass">
                                        <i class="eye-open fa-regular fa-eye"></i>
                                    </div>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-row d-flex justify-content-between m-b30">
								<div class="form-group">
								   <div class="custom-control custom-checkbox">
										<input type="checkbox" class="form-check-input" id="basic_checkbox_1">
										<label class="form-check-label" for="basic_checkbox_1">Remember Me</label>
									</div>
								</div>
								<div class="form-group">
									<a class="text-primary" href="{{"/forgot-password"}}">Forgot Password</a>
								</div>
							</div>

                            {{-- Submit --}}
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-secondary btnhover text-uppercase me-2 sign-btn">
                                    Sign In
                                </button>
                                <a href="{{ route('register.form') }}" class="btn btn-outline-secondary btnhover text-uppercase">
                                    Register
                                </a>

                                <div class="mt-3">
                                    <a href="{{ route('guest.login') }}" class="btn btn-warning btnhover text-uppercase">
                                        Continue as Guest
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </section>
    </div>
</div>

@endsection

@section('script')
<script>
	$(document).ready(function() {

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
	});

</script>
@endsection