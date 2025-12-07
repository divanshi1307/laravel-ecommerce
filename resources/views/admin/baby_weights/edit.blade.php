@extends('layouts.app')

@section('content')

<div class="container">
    <div class="title">
        <h1 class="text-dark float-left">Baby Weight</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
        <div class="loader" style="display:none;">
            <p><i class="fa fa-spinner fa-5x fa-spin"></i></p>
        </div>

        <h2>Edit</h2>
        <form action="{{ route('baby-weight.update', $baby_weight->slug) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="col-sm-2 float-right text-right mb-4">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save pr-1"></i> Save
                </button>
            </div>

            <div class="clearfix"></div>

            <div class="form-group">
                <label class="form-label">Enter Weight Range<span class="text-danger">*</span></label>

                <input type="text" name="weight_range" class="form-control @error('weight_range') is-invalid @enderror"
                       value="{{ old('weight_range', $baby_weight->weight_range) }}" placeholder="e.g., 0-5 kg">

                @error('weight_range')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>
</div>
@endsection
