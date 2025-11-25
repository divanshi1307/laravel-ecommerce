@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
	    <h1 class="text-dark float-left">Products</h1>
        <a class="btn btn-primary btn-sm float-right" href="{{ route('products.create') }}" data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Category">
        	<i class="fa fa-plus-circle text-light"></i> Add
        </a>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
    	<h2>List</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="get">
			<div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
				<h5 class="col-sm-12 py-2 m-0 border-bottom-info bg-white">Filter</h5>			
				<div class="form-group col-sm p-0">
					<label class="col-sm-12">Category</label>
					<div class="col-sm-12">
						<select name="category_id" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group col-sm p-0">
					<label class="col-sm-12">Product Name</label>
					<div class="col-sm-12">
						<input type="text" name="title" class="form-control" placeholder="Enter Product Name" value="{{ request('title') }}" autocomplete="off">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group col-sm p-0">
				    <label class="col-sm-12">Status</label>
					<div class="col-sm-12">
						<select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group col-sm p-0">
				    <label class="col-sm-12">Stock</label>
					<div class="col-sm-12">
						<select name="stock_status" class="form-control">
                            <option value="">All Stock</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group col-sm-3 p-0 text-right">
					<label class="col-sm-12">&nbsp;</label>
					<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fa fa-filter pr-1"></i> submit</button>
						<a href="{{ route('products.index') }}" class="btn btn-danger btn-sm">Clear Filters</a>
					</div>
				</div>
			</div>
		</form>

        <table class="table table-bordered table-md">
            <thead class="bg-light">
                <tr>
                    <th>#</a></th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Name</th>
                    {{-- <th>Price</th> --}}
                    <th>Stock</th>
                    <th>Stock Status</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
                	
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @php
                                $imageIds = explode(',', $product->images);
                                $firstImage = \App\Models\Upload::find($imageIds[0] ?? null);
                            @endphp

                            @if ($firstImage)
                                <img src="{{ asset('uploads/products/' . $firstImage->file_name) }}" width="60" class="rounded">
                            @else
                                <img src="{{ asset('uploads/images/dummy.png') }}" width="60" class="rounded">
                            @endif
                        </td>
                        <td>{{ $product->category->category_name ?? 'No Category' }}</td>
                        <td>
                            <span class="fw-bold px-2 py-1 bg-secondary text-white rounded">
                                {{ $product->title }}</span><br> 
                                Item Code: {{ $product->product_item_code }} <br> 
                                Type: {{ ucfirst($product->product_type) }}
                        </td>

                        <td>{{ $product->stock_quantity ?? 'N/A'}}</td>
                        <td>
                            @if($product->stock_status === 'in_stock')
                                <span class="badge bg-success">In Stock</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                        <td>
                            <select name="is_active" class="form-select form-select-sm changeStatus" data-id="{{ $product->id }}">
                                <option value="1" {{ $product->is_active == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $product->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        
                        <td class="text-right">
                            <a href="{{ route('products.edit',$product->id) }}" class="btn btn-primary btn-sm mb-1" title="edit"><i class="fa fa-edit"></i></a>
                            <form method="post" action="{{ route('products.destroy',$product->id) }}" onsubmit="return confirm('Are you sure want to delete?');"style="display:inline">
                                <input type="hidden" name="_token" value="U8RYQ3sykTyo2Jj1wUbsuBusAJVproBooXwsp1zO">                                <input type="hidden" name="table" value="products">
                                <input type="hidden" name="action" value="products">
                                <input type="hidden" name="id" value="105">
                                <button type="submit" class="btn btn-danger btn-sm delete mb-1" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No products found.</td></tr>
                @endforelse           
            </tbody>
        </table>

        <div class="co-sm-12">
            <div class="float-left">{{ $products->links() }}</div>
            <div class="text-left float-left">showing {{ $products->count() }} of {{ $products->total() }} Records</div>
            <div class="clearfix"></div>
        </div>   
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '.changeStatus', function() {
            var status = $(this).val();
            var productId = $(this).data('id');

            $.ajax({
                url: "{{ route('products.updateStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: productId,
                    is_active: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message); 
                    } else {
                        toastr.error('Something went wrong!');
                    }
                },
                error: function() {
                    toastr.error('Failed to update status!');
                }
            });
        });
    </script>
@endsection

