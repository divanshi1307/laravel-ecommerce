@extends('layouts.app')

@section('content')
<div class="container">
    <div class="title">
        <h1 class="text-dark float-left">Adult Waist Size</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
        <div class="loader" style="display:none;">
            <p><i class="fa fa-spinner fa-5x fa-spin"></i></p>
        </div>

        <h2>Add Waist Size</h2>
        <form action="{{ route('adult-waist.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="col-sm-2 float-right text-right mb-4">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save pr-1"></i> Save
                </button>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="form-label">Waist Size<span class="text-danger">*</span></label>
                <input type="text" name="waist_size" 
                       class="form-control @error('waist_size') is-invalid @enderror"
                       value="{{ old('waist_size') }}" placeholder="e.g., 34">
                @error('waist_size')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
</div>
@endsection
