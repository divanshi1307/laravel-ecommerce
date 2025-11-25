@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
	    <h1 class="text-dark float-left">Brand</h1>
        <a class="btn btn-primary btn-sm float-right" href="{{ route('brands.create') }}" data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Category">
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

            <form method="GET" action="{{ route('brands.index') }}">
                <div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
                    <h5 class="col-sm-12 py-2 m-0 border-bottom-info bg-white">Filter</h5>

                    <div class="form-group col-sm-9 p-0">
                        <label class="col-sm-12">Brand Name</label>
                        <div class="col-sm-12">
                            <input type="text" 
                                name="name" 
                                class="form-control" 
                                placeholder="Enter Brand Name"
                                value="{{ request('name') }}" 
                                autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group col-sm-3 p-0 text-right">
                        <label class="col-sm-12">&nbsp;</label>
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">
                                <i class="fa fa-filter pr-1"></i> Submit
                            </button>
                            <a href="{{ route('brands.index') }}" class="btn btn-danger btn-sm">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-md">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Brand Name</th>
                        <th>Brand Logo</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $k => $brand)
                        <tr>
                            <td class="text-center">
                                {{ $k + 1 + ($brands->currentPage() - 1) * $brands->perPage() }}
                            </td>
                            <td>{{ $brand->brand_name }}</td>
                            <td>
                                @if($brand->brand_logo)
                                    <img src="{{ asset('storage/'.$brand->brand_logo) }}" width="60" class="rounded">
                                @else
                                    <img src="{{ asset('uploads/images/dummy.png') }}" width="60" class="rounded">
                                @endif
                            </td>
                            <td>{{ $brand->sort_order }}</td>
                            
                            {{-- <td>{{ $brand->show_on_homepage ? 'Yes' : 'No' }}</td> --}}
                            <td>
                                <select name="is_active" class="form-select form-select-sm changeStatus" data-id="{{ $brand->id }}">
                                    <option value="1" {{ $brand->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $brand->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i>Edit</a>

                                <form method="post" action="{{ route('brands.destroy', $brand->id) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete" fdprocessedid="gdgnob" onclick="return confirm('Are you sure want to delete?')">
                                        <i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No Brands Found</td></tr>
                    @endforelse
                </tbody>
            </table>

        <div class="co-sm-12">
            <div class="float-left">{{ $brands->links() }}</div>
            <div class="text-left float-left">showing {{ $brands->count() }} of {{ $brands->total() }} Records</div>
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
                url: "{{ route('brands.updateStatus') }}",
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

        // $(document).on('change', '.changeStatus', function() {
        //     var status = $(this).val();
        //     var productId = $(this).data('id');

        //     $.ajax({
        //         url: "{{ route('brands.updateStatus') }}",
        //         type: "POST",
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             id: productId,
        //             is_active: status
        //         },
        //         success: function(response) {
        //             if (response.success) {
        //                 toastr.success(response.message); 
        //             } else {
        //                 toastr.error('Something went wrong!');
        //             }
        //         },
        //         error: function() {
        //             toastr.error('Failed to update status!');
        //         }
        //     });
        // });
    </script>
@endsection
