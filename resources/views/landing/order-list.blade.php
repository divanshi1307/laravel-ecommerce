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
                        <aside class="col-xl-3">
                            <div class="toggle-info">
                                <h5 class="title mb-0">Account Navbar</h5>
                                <a class="toggle-btn" href="#accountSidebar">Account Menu</a>
                            </div>
                            <div class="sticky-top account-sidebar-wrapper">
                                <div class="account-sidebar" id="accountSidebar">
                                    <div class="profile-head">
                                        <div class="user-thumb">

                                            <img src="{{ auth()->user()->profile_image ? asset('uploads/profile/' . auth()->user()->profile_image) : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }}" 
                                            alt="User Avatar" class="rounded-circle">

                                        </div>
                                        <h5 class="title mb-0">{{ auth()->user()->name }}</h5>
                                        <span class="text text-primary">{{ auth()->user()->email }}</span>
                                    </div>
                                    <div class="account-nav">
                                        <div class="nav-title bg-light">DASHBOARD</div>
                                        <ul>
                                            <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                                            <li><a href="{{route('orders.index')}}">Orders</a></li>
                                        </ul>
                                        <div class="nav-title bg-light">ACCOUNT SETTINGS</div>
                                        <ul class="account-info-list">
                                            <li><a href="{{route('account.profile')}}">Profile</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </aside>
                        <div class="col-xl-9 account-wrapper">
                            <div class="account-card">
                                <div class="table-responsive table-style-1">
                                    <table class="table table-hover mb-3">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date Purchased</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                                <tr>
                                                    <td>
                                                        <a href="#" class="fw-medium">
                                                            {{ $order->order_id }}
                                                        </a>
                                                    </td>

                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>

                                                    <td>
                                                        <span class="badge 
                                                            @if($order->status == 'pending') bg-info 
                                                            @elseif($order->status == 'canceled') bg-danger
                                                            @elseif($order->status == 'delivered') bg-success
                                                            @else bg-warning 
                                                            @endif
                                                        ">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>

                                                    <td>₹{{ number_format($order->total) }}</td>

                                                    <td>
                                                        <a href="#" class="btn-link text-underline p-0">View</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No orders found.</td>
                                                </tr>
                                            @endforelse
                                            </tbody>

                                    </table>
                                </div>
                                
                                <!-- Pagination-->
                                <div class="d-flex">
                                    {{ $orders->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection