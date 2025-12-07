@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
        <h1 class="text-dark float-left">Baby Weight</h1>

        <a class="btn btn-primary btn-sm float-right" 
           href="{{ route('baby-weight.create') }}">
            <i class="fa fa-plus-circle text-light"></i> Add
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
    <form method="get" action="{{ route('baby-weight.index') }}">
        <div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
            <h5 class="col-sm-12 py-2 m-0 bg-white">Filter</h5>

            <div class="form-group col-sm-9 p-0">
                <label class="col-sm-12">Baby Weight</label>
                <div class="col-sm-12">
                    <input type="text" name="weight_range" class="form-control" placeholder="Enter Weight" value="{{ request('weight_range') }}">
                </div>
            </div>

            <div class="form-group col-sm-3 p-0 text-right">
                <label class="col-sm-12">&nbsp;</label>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary btn-sm mr-2">
                        <i class="fa fa-filter"></i> Submit
                    </button>
                    <a href="{{ route('baby-weight.index') }}" 
                       class="btn btn-danger btn-sm">Clear</a>
                </div>
            </div>

        </div>
    </form>

    {{-- Table --}}
    <table class="table table-bordered table-md">
        <thead class="bg-light">
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Baby Weight</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($babyWeights as $k => $weight)
                <tr>
                    <td class="text-center">
                        {{ $k + 1 + ($babyWeights->currentPage() - 1) * $babyWeights->perPage() }}
                    </td>

                    <td class="text-center">{{ $weight->weight_range }}</td>

                    <td class="text-right">
                        <a href="{{ route('baby-weight.edit', $weight->slug) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil"></i> Edit
                        </a>

                        <form method="POST" action="{{ route('baby-weight.destroy', $weight->slug) }}" 
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
                    <td colspan="4" class="text-center">No baby weights found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div>
        <div class="float-left">{{ $babyWeights->links() }}</div>
        <div class="text-left float-left ml-2">
            Showing {{ $babyWeights->count() }} of {{ $babyWeights->total() }} Records
        </div>
        <div class="clearfix"></div>
    </div>

</div>
@endsection
