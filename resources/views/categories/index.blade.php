@extends('layouts.app')

@section('content')
<div class="container">

	<div class="title">
	    <h1 class="text-dark float-left">Category</h1>
        <a class="btn btn-primary btn-sm float-right" href="{{ route('categories.create') }}" data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Category">
        	<i class="fa fa-plus-circle text-light"></i> Add
        </a>
        <div class="clearfix"></div>
    </div>

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
    
    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
    	<h2>List</h2>
                
        <form method="get" action="{{ route('categories.index') }}">
			<div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
				<h5 class="col-sm-12 py-2 m-0 border-bottom-info bg-white">Filter</h5>
				<div class="form-group col-sm-9 p-0">
					<label class="col-sm-12">Category Name</label>
					<div class="col-sm-12">
						<input type="text" name="name" class="form-control" placeholder="Enter Category Name" value="{{ request('name') }}" autocomplete="off">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group col-sm-3 p-0 text-right">
					<label class="col-sm-12">&nbsp;</label>
					<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fa fa-filter pr-1"></i> submit</button>
						<a href="{{ route('categories.index') }}" class="btn btn-danger btn-sm">Clear Filters</a>
					</div>
				</div>
			</div>
		</form>
        
		<table class="table table-bordered table-md">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Sort Order</th>
                    <th>Status</th> 
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $k => $category)
                    <tr>
                        <td>
                            {{ $k + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}
                        </td>
                        
                        <td>
                            {{ $category->parent ? $category->parent->category_name : 'N/A' }}
                        </td>
                        <td>{{ $category->category_name }}</td>

                        {{-- <td>
                            @if($category->subcategory_image_small)
                                <img src="{{ asset('storage/' . $category->subcategory_image_small) }}" alt="Banner" width="100">
                            @else
                                <img src="{{ asset('uploads/images/dummy.png') }}" width="60" class="rounded">
                            @endif
                        </td>
                        <td>
                            @if($category->subcategory_image_large)
                                <img src="{{ asset('storage/' . $category->subcategory_image_large) }}" alt="seo_image" width="100">
                            @else
                                <img src="{{ asset('uploads/images/dummy.png') }}" width="60" class="rounded">
                            @endif
                        </td>  --}}

                        <td>{{ $category->sort_order }}</td>
                        <td>
                            <select name="is_active" class="form-select form-select-sm changeStatus" data-id="{{ $category->id }}">
                                <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        
                        <td class="text-right">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil"></i>Edit
                            </a>
                            <form method="post" action="{{ route('categories.destroy', $category->id) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete" fdprocessedid="gdgnob" onclick="return confirm('Are you sure want to delete?')">
                                    <i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No categories found.</td>
                    </tr>
                @endforelse
                                
            </tbody>
        </table>
        <div class="co-sm-12">
            <div class="float-left">{{ $categories->links() }}</div>
            <div class="text-left float-left">showing {{ $categories->count() }} of {{ $categories->total() }} Records</div>
            <div class="clearfix"></div>
        </div>   
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '.changeStatus', function() {
            var selectBox = $(this);          
            var newStatus = selectBox.val();  
            var productId = selectBox.data('id');

            $('.loader').show(); 

            $.ajax({
                url: "{{ route('category.updateStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: productId,
                    is_active: newStatus
                },
                success: function(response) {

                    $('.loader').hide(); 

                    if (response.success) {
                        alert("Status updated successfully!");
                        selectBox.val(newStatus);
                    } else {
                        alert("Something went wrong!");
                    }
                },
                error: function() {
                    $('.loader').hide();
                    alert("Failed to update status!");
                }
            });
        });

    </script>
@endsection
