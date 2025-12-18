@extends('layouts.app')

@section('content')
<div class="container">

	<div class="title">
	    <h1 class="text-dark float-left">Coupons</h1>
		<a class="btn btn-primary btn-sm float-right" href="{{ url('/admin/addcoupon') }}" title="Add">
        	<i class="fa fa-plus-circle text-light"></i> Add
        </a>
        <div class="clearfix"></div>
    </div>
    
    <div class="content">        
    	<h2>List</h2>
        @if( session()->has('status'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> {{ session()->get('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
              	<p class="m-0 pb-2"><i class="fa fa-exclamation-triangle"></i> {{ $error }}</p>
              @endforeach
            </div>
        @endif
		<div class="py-2">
		    <a href="{{url('admin/coupons')}}" class="btn btn-dark btn-sm mr-2">All Coupons</a>
		    <a href="{{url('admin/product_specific_coupons')}}" class="btn btn-sm text-dark border border-dark mr-2">Product Specific Coupons</a>
		</div>
		@if(count($results)>0)
			<table class="table table-bordered table-stripped">
				<thead class="bg-light">
					<tr>
						<th>Sr.No.</th>
						<th>Code</th>
						<th>Type</th>
						<th>Amount</th>
						<th>Uses per Users</th>
						<th>Min Order Total</th>
						<th>Orders</th>
						<th>Status</th>
						<th width="180" class="text-right">Action</th>
					</tr>
				</thead>
				@php 
					$i=1; 
				@endphp
				@foreach($results as $row)
					<tr>
						<td>{{ $i }}</td>
						<td>{{ $row->code }}</td>
						<td>{{ ucwords($row->type) }}</td>
						<td>{{ $row->amt }}</td>
						<td>{{ $row->uses_per_user }}</td>
						<td>{{ Config::get('app.currency_symbol')}} {{ $row->min_order_total }}</td>
						<td><h6>{{ \App\Models\Order::where('coupon_id',$row->id)->count() }}</h6></td>
						<td>{{ $row->status==1 ? "Active" : "In active" }}</td>
						<td class="text-right">
							<a href="{{ url('/admin/addcoupon/'.$row->id) }}" class="btn btn-primary btn-sm">
								<i class="fa fa-pencil"></i> Edit
							</a>
							<form method="post" action="{{ route('coupon.delete') }}" style="display:inline">
                                @csrf
                                <input type="hidden" name="table" value="coupons">
                                <input type="hidden" name="action" value="coupons">
                                <input type="hidden" name="id" value="{{ $row->id }}">
                                <button type="submit" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                            </form>
						</td>
					</tr>
					@php $i++; @endphp
				@endforeach
			</table>
			<div class="co-sm-12">
				<div class="float-left">{{ $results->links() }}</div>
				<div class="text-left float-left">showing {{ $results->count() }} of {{ $results->total() }} Records</div>
				<div class="clearfix"></div>
			</div>
		@else
			<div class="alert alert-info">
			  No results !
			</div>
		@endif
        
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
	$('.delete').on('click',function(){
		if( !confirm("sure you want to delete ?") ){				
			return false;
		}
	});
});
</script>
@endsection
