@extends('landing.layout')

@section('content')

    <div class="page-wraper">
        <div class="page-content bg-light">
		    <!--Banner Start-->
            <div class="dz-bnr-inr bg-secondary overlay-black-light" style="background-image:url(images/background/bg1.jpg);">
                <div class="container">
                    <div class="dz-bnr-inr-entry">
                        <h1>Review</h1>
                        <nav aria-label="breadcrumb" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{"/"}}"> Home</a></li>
                                <li class="breadcrumb-item">Review</li>
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
                                            <li><a href="{{ route('orders.index') }}">Orders</a></li>
                                        </ul>
                                        <div class="nav-title bg-light">ACCOUNT SETTINGS</div>
                                        <ul class="account-info-list">
                                            <li><a href="{{route('account.profile')}}">Profile</a></li>
                                            <li><a href="{{route('account.reviews')}}">Review</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </aside>
                        <section class="col-xl-9 account-wrapper">
                            <div class="row">

                                @forelse ($reviews as $review)
                                    <div class="col-md-6 m-b30">
                                        <div class="review-card">
                                            <div class="review-head">
                                                <div class="review-media">
                                                    <img src="{{ asset('uploads/products/' . ($review->product->first_image_url ?? 'no-image.jpg')) }}" alt="">
                                                </div>

                                                <div class="clearfix">
                                                    <h5 class="mb-0">{{ $review->product->title }}</h5>
                                                    {{-- Star Rating --}}
                                                    <div class="star-rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fa fa-star {{ $i <= $review->rating ? 'text-yellow' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <p>{{ $review->comment ?? 'No review text added.' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center text-muted">You haven't written any reviews yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection