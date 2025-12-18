@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
	    <h1 class="text-dark float-left">Location</h1>
        <a class="btn btn-primary btn-sm float-right" href="{{ route('locations.create') }}" data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Category">
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

            <form method="get" action="{{ route('locations.index') }}">
                <div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
                    <h5 class="col-12 py-2 m-0 border-bottom-info bg-white">Filter</h5>

                    <div class="col-12 d-flex flex-wrap align-items-end gap-2 py-3">

                        <div class="mr-2">
                            <label for="state1">State</label>
                            <input type="text" id="state1" name="state" class="form-control" placeholder="State"
                                value="{{ request('state') }}" autocomplete="off">
                        </div>

                        <div class="mr-2">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" class="form-control" placeholder="City"
                                value="{{ request('city') }}" autocomplete="off">
                        </div>

                        <div class="mr-2">
                            <label for="area">Area</label>
                            <input type="text" id="area" name="area" class="form-control" placeholder="Area"
                                value="{{ request('area') }}" autocomplete="off">
                        </div>

                        <div class="mr-2">
                            <label for="pincode">Pincode</label>
                            <input type="text" id="pincode" name="pincode" class="form-control" placeholder="Pincode"
                                value="{{ request('pincode') }}" autocomplete="off">
                        </div>

                        <div class="mr-2" style="min-width: 150px;">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="ml-auto mt-3 mt-md-0 d-flex">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">
                                <i class="fa fa-filter pr-1"></i> Submit
                            </button>

                            <a href="{{ route('locations.index') }}" class="btn btn-danger btn-sm">
                                Clear
                            </a>
                        </div>

                    </div>
                </div>
            </form>


            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Area</th>
                        <th>Pincode</th>
                        <th>Shipping Charge</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $location)
                        <tr>
                            <td>
                                {{ $index + 1 + ($locations->currentPage() - 1) * $locations->perPage() }}
                            </td>
                            <td>{{ $location->state }}</td>
                            <td>{{ $location->city }}</td>
                            <td>{{ $location->area }}</td>
                            <td>{{ $location->pincode }}</td>
                            <td>{{ $location->shipping_charge }}</td>
                            <td>
                                <select name="is_active" class="form-select form-select-sm changeStatus" data-id="{{ $location->id }}">
                                    <option value="1" {{ $location->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $location->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-pencil"></i>Edit
                                </a>
                                <form method="post" action="{{ route('locations.destroy', $location->id) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete" fdprocessedid="gdgnob" onclick="return confirm('Are you sure want to delete?')">
                                        <i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No Location found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="co-sm-12">
                <div class="float-left">{{ $locations->links() }}</div>
                <div class="text-left float-left">showing {{ $locations->count() }} of {{ $locations->total() }} Records</div>
                <div class="clearfix"></div>
            </div>   
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '.changeStatus', function() {
            var selectBox = $(this);          
            var newStatus = selectBox.val();  
            var locationId = selectBox.data('id');

            $('.loader').show(); 

            $.ajax({
                url: "{{ route('locations.updateStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: locationId,
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
