@extends('layouts.app')

@section('content')

<div class="container ">
    <div class="title">
	    <h1 class="text-dark float-left">Age Group</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
    	    <h2>Edit</h2>
            <form action="{{ route('age-groups.update', $age_group->slug) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save pr-1"></i> save
                    </button>
                </div>

                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="form-label">Enter Age Group Title<span class="text-danger">*</span></label>

                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $age_group->name) }}"
                        placeholder="Age Group">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </form>

        </div>
    </div>
@endsection