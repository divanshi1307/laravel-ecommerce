@extends('layouts.app')

@section('content')
<div class="container">
    <div class="title">
	    <h1 class="text-dark float-left">Location</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
    	<h2>Add</h2>
        <form action="{{ route('locations.store') }}" method="POST">
            @csrf

            <div class="col-sm-2 float-right text-right mb-4">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label>State <span class="text-danger">*</span></label>
                <input type="text" name="state" class="form-control" placeholder="State" value="{{ old('state') }}">
                @error('state') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>City <span class="text-danger">*</span></label>
                <input type="text" name="city" class="form-control" placeholder="City" value="{{ old('city') }}">
                @error('city') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Area</label>
                <input type="text" name="area" class="form-control" placeholder="Area" value="{{ old('area') }}">
            </div>

            <div class="form-group">
                <label>Pincode <span class="text-danger">*</span></label>
                <input type="text" name="pincode" class="form-control" placeholder="Pincode" value="{{ old('pincode') }}">
                @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Shipping Charge <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="shipping_charge" class="form-control" placeholder="Shipping Charge" value="{{ old('shipping_charge') }}">
                @error('shipping_charge') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </form>
    </div>
</div>
@endsection
