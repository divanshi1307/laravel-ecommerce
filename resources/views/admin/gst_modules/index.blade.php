@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
        <h1 class="text-dark float-left">GST Module</h1>

        <a class="btn btn-primary btn-sm float-right" 
           href="{{ route('gst-module.create') }}">
            <i class="fa fa-plus-circle text-light"></i> Add GST
        </a>

        <div class="clearfix"></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <form method="get" action="{{ route('gst-module.index') }}">
        <div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
            <h5 class="col-sm-12 py-2 m-0 bg-white">Filter</h5>

            <div class="form-group col-sm-9 p-0">
                <label class="col-sm-12">GST Percentage</label>
                <div class="col-sm-12">
                    <input type="number" name="gst_percentage" class="form-control" 
                           placeholder="Enter GST %" value="{{ request('gst_percentage') }}" step="0.01">
                </div>
            </div>

            <div class="form-group col-sm-3 p-0 text-right">
                <label class="col-sm-12">&nbsp;</label>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary btn-sm mr-2">
                        <i class="fa fa-filter"></i> Submit
                    </button>
                    <a href="{{ route('gst-module.index') }}" class="btn btn-danger btn-sm">Clear</a>
                </div>
            </div>

        </div>
    </form>

    {{-- Table --}}
    <table class="table table-bordered table-md">
        <thead class="bg-light">
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">GST %</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($gstModules as $k => $gst)
                <tr>
                    <td class="text-center">
                        {{ $k + 1 + ($gstModules->currentPage() - 1) * $gstModules->perPage() }}
                    </td>

                    <td class="text-center">{{ $gst->gst_percentage }}</td>

                    <td class="text-right">
                        <a href="{{ route('gst-module.edit', $gst->slug) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil"></i> Edit
                        </a>

                        <form method="POST" action="{{ route('gst-module.destroy', $gst->slug) }}" 
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure want to delete?')"
                                class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No GST entries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div>
        <div class="float-left">{{ $gstModules->links() }}</div>
        <div class="text-left float-left ml-2">
            Showing {{ $gstModules->count() }} of {{ $gstModules->total() }} Records
        </div>
        <div class="clearfix"></div>
    </div>

</div>
@endsection
