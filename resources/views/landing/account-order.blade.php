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

                            <h4 class="mb-4">My Orders</h4>

                            @if($orders->count())

                                @foreach($orders as $order)
                                    <div class="card shadow-sm border-0 mb-4">
                                        {{-- Header --}}
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
                                            <strong>ORDER ID: {{ $order->order_id }}</strong>

                                            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                                <span class="badge px-3 py-2
                                                    {{ $order->status == 'pending' ? 'badge-warning' : '' }}
                                                    {{ $order->status == 'processing' ? 'badge-info' : '' }}
                                                    {{ $order->status == 'completed' ? 'badge-success' : '' }}">
                                                    Order Status: {{ $order->status() }}
                                                </span>

                                                <a href="{{ url('/orders/view', $order->id) }}"
                                                class="btn btn-outline-dark btn-sm">
                                                    View Order
                                                </a>
                                            </div>
                                        </div>

                                        {{-- Body --}}
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <small class="text-label">TOTAL AMOUNT</small>
                                                    <div>₹ {{ number_format($order->total, 2) }}</div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <small class="text-label">PAYMENT METHOD</small>
                                                    <div>{{ strtoupper($order->payment_method) }}</div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <small class="text-label">ORDERED ON</small>
                                                    <div>{{ $order->created_at->format('d-m-Y H:i:s') }}</div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <small class="text-label">MOBILE</small>
                                                    <div>{{ $order->phone }}</div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <small class="text-label">CUSTOMER NAME</small>
                                                    <div>{{ $order->first_name }} {{ $order->last_name }}</div>
                                                </div>

                                                <div class="col-md-9 col-sm-12 mb-3">
                                                    <small class="text-label">DELIVERY ADDRESS</small>
                                                    <div>
                                                        {{ $order->street_address }},
                                                        {{ $order->city }},
                                                        {{ $order->state }} - {{ $order->pincode }}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{ $orders->links() }}

                            @else
                                <div class="alert alert-info">No orders yet!</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection