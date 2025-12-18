
@extends('landing.layout')

@section('content')


        <div class="page-wraper">
        <div class="page-content bg-light">
            <!--Banner Start-->
            <div class="dz-bnr-inr bg-secondary overlay-black-light" style="background-image:url(images/background/bg1.jpg);">
                <div class="container">
                    <div class="dz-bnr-inr-entry">
                        <h1>Orders</h1>
                        <nav aria-label="breadcrumb" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{"/"}}">Home</a></li>
                                <li class="breadcrumb-item">Orders</li>
                            </ul>
                        </nav>
                    </div>
                </div>	
            </div>
            <!--Banner End-->
            
            <div class="content-inner-1">
                <div class="container">
                    <div class="row">
                        {{-- Sidebar --}}
                        <aside class="col-xl-3 col-lg-4 mb-4">
                            <div class="toggle-info">
                                <h5 class="title mb-0">Account Navbar</h5>
                                <a class="toggle-btn" href="#accountSidebar">Account Menu</a>
                            </div>

                            <div class="sticky-top account-sidebar-wrapper">
                                <div class="account-sidebar" id="accountSidebar">
                                    <div class="profile-head text-center">
                                        <img src="{{ auth()->user()->profile_image 
                                            ? asset('uploads/profile/' . auth()->user()->profile_image) 
                                            : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }}"
                                            class="rounded-circle mb-2" width="80">

                                        <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                                        <span class="text-danger">{{ auth()->user()->email }}</span>
                                    </div>

                                    <div class="account-nav mt-4">
                                        <div class="nav-title bg-light">DASHBOARD</div>
                                        <ul>
                                            <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                                            <li class="active"><a href="{{ route('orders.index') }}">Orders</a></li>
                                        </ul>

                                        <div class="nav-title bg-light mt-3">ACCOUNT SETTINGS</div>
                                        <ul>
                                            <li><a href="{{ route('account.profile') }}">Profile</a></li>
                                            <li><a href="{{ route('account.reviews') }}">Review</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </aside>

                        {{-- Orders Section --}}
                        <div class="col-xl-9 col-lg-8">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">Order Detail</h4>
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('orders.index') }}">
                                    ← Back to Orders
                                </a>
                            </div>

                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <strong>ORDER ID: {{ $order->order_id }}</strong>

                                    <span class="badge px-3 py-2 order-status-{{ $order->status }}">
                                        {{ $order->status() }}
                                    </span>
                                </div>

                                <div class="card-body">

                                    {{-- Items --}}
                                    <p class="text-muted fw-bold mb-2">ITEMS</p>

                                    @php
                                        $subtotal = 0;
                                    @endphp

                                    @foreach($order->items as $item)
                                        @php
                                            $totalItemPrice = $item->price * $item->quantity;
                                            $subtotal += $totalItemPrice;
                                        @endphp

                                        <div class="row align-items-center mb-3">
                                            <div class="col-md-1">
                                                <img src="{{ asset('uploads/products/' . $item->product->display_image) }}"
                                                    alt="{{ $item->product->title }}"
                                                    class="img-fluid rounded border"
                                                    width="70">
                                            </div>

                                            <div class="col-md-7">
                                                <strong>{{ $item->quantity }} × {{ $item->product->title ?? 'Product' }}</strong>
                                                @if($item->variant_name)
                                                    <div class="text-muted small">
                                                        {{ $item->variant_name }} : {{ $item->variant_option_name }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-4 text-end fw-bold fs-5">
                                                ₹ {{ number_format($totalItemPrice, 2) }}
                                            </div>
                                        </div>

                                        <hr>
                                    @endforeach

                                    @php
                                        $couponCode = session('coupon_code');
                                        $discountAmount = session('discount') ?? 0;
                                        $finalTotal = $subtotal + ($shippingCharge ?? 0) - $discountAmount;

                                        if ($finalTotal < 0) $finalTotal = 0;
                                    @endphp

                                    <div class="row my-3 text-right text-dark">
                                        <div class="col-6 fw-bold fs-5">Subtotal</div>
                                        <div class="col-6 fw-bold fs-5 text-end">₹ {{ number_format($subtotal) }}</div>
                                    </div>

                                    @if($couponCode)
                                    <div class="row my-3 text-right text-dark">
                                        <div class="col-6 fw-bold fs-5">Coupon ({{ $couponCode }})</div>
                                        <div class="col-6 fw-bold fs-5 text-end text-success">- ₹ {{ number_format($discountAmount) }}</div>
                                    </div>
                                    @endif

                                    @if(!empty($shippingCharge) && $shippingCharge > 0)
                                    <div class="row my-3 text-right text-dark">
                                        <div class="col-6 fw-bold fs-5">Shipping Charge</div>
                                        <div class="col-6 fw-bold fs-5 text-end">₹ {{ number_format($shippingCharge) }}</div>
                                    </div>
                                    @endif

                                    <div class="row my-3 text-right text-dark">
                                        <div class="col-6 fw-bold fs-5">Order Total</div>
                                        <div class="col-6 fw-bold fs-5 text-end">₹ {{ number_format($finalTotal) }}</div>
                                    </div>

                                    <hr>

                                    {{-- Info Grid --}}
                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-2">
                                            <small class="text-label">PAYMENT METHOD</small><br>
                                            {{ strtoupper($order->payment_method) }}
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <small class="text-label">ORDERED ON</small><br>
                                            {{ $order->created_at->format('d-m-Y H:i:s') }}
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <small class="text-label">MOBILE</small><br>
                                            {{ $order->phone }}
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <small class="text-label">EMAIL</small><br>
                                            {{ $order->email }}
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <small class="text-label">DELIVERY ADDRESS</small><br>
                                            {{ $order->street_address }},
                                            {{ $order->city }},
                                            {{ $order->state }} - {{ $order->pincode }}
                                        </div>
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