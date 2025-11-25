@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="title">
            <h1 class="text-dark float-left">Slider</h1>
            <a class="btn btn-primary btn-sm float-right" href="{{ route('sliders.create') }}" data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Category">
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

            <form method="GET" action="{{ route('sliders.index') }}">
                <div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
                    <h5 class="col-sm-12 py-2 m-0 border-bottom-info bg-white">Filter</h5>

                    <div class="form-group col-sm-9 p-0">
                        <label class="col-sm-12">Title</label>
                        <div class="col-sm-12">
                            <input type="text" 
                                name="title" 
                                class="form-control" 
                                placeholder="Enter Title"
                                value="{{ request('title') }}" 
                                autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group col-sm-3 p-0 text-right">
                        <label class="col-sm-12">&nbsp;</label>
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">
                                <i class="fa fa-filter pr-1"></i> Submit
                            </button>
                            <a href="{{ route('sliders.index') }}" class="btn btn-danger btn-sm">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Photo</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        {{-- <th>Banner Link</th>  --}}
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $k => $slider)
                    <tr>
                        <td class="text-center">
                            {{ $k + 1 + ($sliders->currentPage() - 1) * $sliders->perPage() }}
                        </td>
                        <td>{{ $slider->title ?? 'N/A'}}</td>
                        <td>
                            @if($slider->photoUpload && $slider->photoUpload->file_name)
                                <img src="{{ asset('uploads/sliders/' . $slider->photoUpload->file_name) }}" width="100" class="rounded">
                            @else
                                <img src="{{ asset('uploads/images/dummy.png') }}" width="100" class="rounded">
                            @endif
                        </td>
                        <td>{{ $slider->start_date ?? 'N/A'}}</td>
                        <td>{{ $slider->end_date ?? 'N/A' }}</td>
                        {{-- <td>{{ $slider->banner_link }}</td> --}}

                        <td class="text-right">
                            <a href="{{ route('sliders.edit', $slider->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil"></i>Edit
                            </a>
                            <form method="post" action="{{ route('sliders.destroy', $slider->id) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete" fdprocessedid="gdgnob" onclick="return confirm('Are you sure want to delete?')">
                                    <i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Slider found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="co-sm-12">
                <div class="float-left">{{ $sliders->links() }}</div>
                <div class="text-left float-left">showing {{ $sliders->count() }} of {{ $sliders->total() }} Records</div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection
