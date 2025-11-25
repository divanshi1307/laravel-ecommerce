@extends('layouts.app')

@section('content')

@php

$products = \App\Models\Product::count();

$categories = \App\Models\Category::count();
@endphp

<div class="container">

	<div class="title">
	    <h1 class="text-dark">Dashboard</h1>    	
    </div>
    
    <div class="content dashboard">        
    	@if( session()->has('status'))
            <div class="alert alert-success">
                {{ session()->get('status') }}
            </div>
        @endif
        
        @if( request()->has('user') and request()->user=="invalid" )
            <div class="alert alert-danger">
                Unauthorized Access !
            </div>
        @endif
        
        <br>
		<div class="row mx-0">
			<div class="col-lg-12 col-md-12 col-sm-12 position-relative row">
				<div class="col-sm-3 px-2 text-center mb-3">
					<a href="{{url('/admin/categories')}}" class="d-block bg-warning border border-primary rounded h-100 box-shadow">
						<div class="float-left col-sm-5 px-0 h-100 py-3 text-center">
							<i class="fa fa-shopping-cart fa-5x text-white"></i>
						</div>
						<div class="float-right col-sm-7 px-0 py-3 text-dark">
							<h6>Categories</h6>
							<h1>{{$categories}}</h1>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
				<div class="col-sm-3 px-2 text-center mb-3">
					<a href="{{url('/admin/products')}}" class="d-block bg-warning border border-primary rounded h-100 box-shadow">
						<div class="float-left col-sm-5 px-0 h-100 py-3 text-center">
							<i class="fa fa-tags fa-5x text-white"></i>
						</div>
						<div class="float-right col-sm-7 px-0 py-3 text-dark">
							<h6>Products</h6>
							<h1>{{$products}}</h1>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>

				<div class="col-sm-3 px-2 text-center mb-3">
					<a href="{{url('/admin/brands')}}" class="d-block bg-warning border border-primary rounded h-100 box-shadow">
						<div class="float-left col-sm-5 px-0 h-100 py-3 text-center">
							<i class="fa fa-tags fa-5x text-white"></i>
						</div>
						<div class="float-right col-sm-7 px-0 py-3 text-dark">
							<h6>Brands</h6>
							<h1>{{$brands}}</h1>
						</div>
						<div class="clearfix"></div>
					</a>
				</div>
				
			</div>
			{{-- <div class="col-lg-6 col-md-12 col-sm-12 position-relative py-4">
				<div class="border p-3 rounded box-shadow">
					<div id="OrdersChart" style="height: 400px; width: 100%;"></div>
				</div>
			</div>
			<div class="col-lg-6 col-md-12 col-sm-12 position-relative py-4">
				<div class="border p-3 rounded box-shadow">
					<div id="RevenueChart" style="height: 400px; width: 100%;"></div>
				</div>
			</div> --}}
        </div>
    </div>
</div>

@endsection
