@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="title">
            <h1 class="text-dark float-left">Slider</h1>
            <div class="clearfix"></div>
        </div>

        <div class="content">   
            <h2>Add</h2>

            <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>
                
                <div class="form-group">
                    <label>Title<span class="text-danger">*</span></label>
                    <input type="text" name="title" placeholder="Title" class="form-control @error('title') is-invalid @enderror" 
                        value="{{ old('title') }}" required>
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Photo<span class="text-danger">*</span></label>
                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                    @error('photo')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="datetime-local" name="start_date" 
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="datetime-local" name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label>Banner Link</label>
                    <input type="url" name="banner_link" placeholder="Banner Link" class="form-control @error('banner_link') is-invalid @enderror" 
                        value="{{ old('banner_link') }}">
                    @error('banner_link')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
@endsection
