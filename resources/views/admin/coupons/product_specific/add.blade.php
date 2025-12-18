@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="title">
            <h1 class="text-dark float-left">Product Specific Coupons</h1>
            <a class="btn btn-primary btn-sm float-right" href="{{ url('/admin/product_specific_coupons') }}" title="Back to list">
                <i class="fa fa-reply"></i>
            </a>
            <div class="clearfix"></div>  	
        </div>
        
        <div class="content">        
            <h2>Add</h2>
            @if( session()->has('status'))
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> {{ session()->get('status') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="m-0 p-1"><i class="fa fa-exclamation-triangle"></i> {{ $error }}</p>
                @endforeach
                </div>
            @endif
            <form method="post" action="{{ url('/admin/add_product_specific_coupon/'.$id) }}">
                {{ csrf_field() }}
                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Code</label>
                    <div class="float-left col-sm-10">
                        <input type="text" name="code" class="form-control" placeholder="Code" value="{{ isset($data) ? $data->code : "" }}" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Short Description</label>
                    <div class="float-left col-sm-10">
                        <textarea name="description" class="form-control" placeholder="Short Description">{{ isset($data) ? $data->description : "" }}</textarea>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Discount Type</label>
                    <div class="float-left col-sm-10 py-2">
                        <input type="radio" required name="type" value="fixed" @if(isset($data) and $data->type=='fixed') checked @endif > Fixed <span class="mx-3"></span>
                        <input type="radio" required name="type" value="percent" @if(isset($data) and $data->type=='percent') checked @endif> Percent
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Discount Amount</label>
                    <div class="float-left col-sm-10 position-relative">
                        <input type="text" name="amt" class="form-control" placeholder="Discount Amount" value="{{ isset($data) ? $data->amt : "" }}" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Status</label>
                    <div class="float-left col-sm-10 py-2">
                        <input type="radio" required name="status" value="1" @if(isset($data) and $data->status==1) checked @endif > Active <span class="mx-3"></span>
                        <input type="radio" required name="status" value="0" @if(isset($data) and $data->status==0) checked @endif> Deactive
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Product</label>
                    <div class="float-left col-sm-10 py-2">
                        <select name="product_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" 
                                    @if(isset($data) && $p->id == $data->product_id) selected @endif>
                                    {{ $p->title }}
                                </option>
                            @endforeach
                        </select>


                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
            
        </div>
    </div>
@endsection