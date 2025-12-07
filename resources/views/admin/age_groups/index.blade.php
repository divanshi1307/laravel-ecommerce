@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
        <h1 class="text-dark float-left">Age Group</h1>
        <a class="btn btn-primary btn-sm float-right" href="{{ route('age-groups.create') }}"
           data-toggle="tooltip" title="Add Age Group">
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content">

        <h2>List</h2>

        {{-- FILTER --}}
        <form method="get" action="{{ route('age-groups.index') }}">
            <div class="form-group border bg-light border-info row mt-3 mb-5 mx-0">
                <h5 class="col-sm-12 py-2 m-0 border-bottom-info bg-white">Filter</h5>

                <div class="form-group col-sm-9 p-0">
                    <label class="col-sm-12">Age Group</label>
                    <div class="col-sm-12">
                        <input type="text" name="name" class="form-control" placeholder="Enter Age Group" value="{{ request('name') }}" autocomplete="off">
                    </div>
                </div>

                <div class="form-group col-sm-3 p-0 text-right">
                    <label class="col-sm-12">&nbsp;</label>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm mr-2">
                            <i class="fa fa-filter"></i> Submit
                        </button>
                        <a href="{{ route('age-groups.index') }}" class="btn btn-danger btn-sm">Clear</a>
                    </div>
                </div>

            </div>
        </form>

        {{-- TABLE --}}
        <table class="table table-bordered table-md">
            <thead class="bg-light">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Age Group</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ageGroups as $k => $group)
                    <tr>
                        <td class="text-center">
                            {{ $k + 1 + ($ageGroups->currentPage() - 1) * $ageGroups->perPage() }}
                        </td>

                        <td class="text-center">{{ $group->name }}</td>

                        <td class="text-right">
                            <a href="{{ route('age-groups.edit', $group->slug) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil"></i> Edit
                            </a>

                            <form method="POST" action="{{ route('age-groups.destroy', $group->slug) }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('Are you sure want to delete?')"
                                        class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No age groups found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="co-sm-12">
            <div class="float-left">{{ $ageGroups->links() }}</div>
            <div class="text-left float-left ml-2">
                Showing {{ $ageGroups->count() }} of {{ $ageGroups->total() }} Records
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

@endsection
