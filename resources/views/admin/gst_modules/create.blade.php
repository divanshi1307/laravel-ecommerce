@extends('layouts.app')

@section('content')
<div class="container">
    <div class="title">
        <h1 class="text-dark float-left">Add GST</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
        <div class="loader" style="display:none;">
            <p><i class="fa fa-spinner fa-5x fa-spin"></i></p>
        </div>

        <h2>Add New GST</h2>
        <form action="{{ route('gst-module.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="col-sm-2 float-right text-right mb-4">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save pr-1"></i> Save
                </button>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="form-label">GST Percentage<span class="text-danger">*</span></label>
                <input type="text" name="gst_percentage" class="form-control @error('gst_percentage') is-invalid @enderror"
                       value="{{ old('gst_percentage') }}" placeholder="e.g., 18" step="0.01">
                @error('gst_percentage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
</div>
@endsection
