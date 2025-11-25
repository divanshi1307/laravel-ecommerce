@extends('layouts.app')

@section('content')
<div class="container">
    <div class="title">
        <h1 class="text-dark float-left">Slider</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
        <h2>Edit</h2>

        <form action="{{ route('sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="col-sm-2 float-right text-right mb-4">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label>Title<span class="text-danger">*</span></label>
                <input type="text" name="title" 
                    class="form-control @error('title') is-invalid @enderror" 
                    value="{{ old('title', $slider->title) }}" required>
                @error('title')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Photo<span class="text-danger">*</span></label>
                <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror">

                @if($slider->photoUpload && $slider->photoUpload->file_name)
                    <img id="sliderPreview" 
                        src="{{ asset('uploads/sliders/' . $slider->photoUpload->file_name) }}" 
                        width="100" class="mt-2 rounded">
                @else
                    <img id="sliderPreview" 
                        src="{{ asset('uploads/images/dummy.png') }}" 
                        width="100" class="mt-2 rounded">
                @endif

                @error('photo')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="datetime-local" name="start_date" 
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date', $slider->start_date) }}">
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
                            value="{{ old('end_date', $slider->end_date) }}">
                        @error('end_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Banner Link</label>
                <input type="url" name="banner_link" 
                    class="form-control @error('banner_link') is-invalid @enderror" 
                    value="{{ old('banner_link', $slider->banner_link) }}">
                @error('banner_link')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script> 
        $('#photo').change(function(e) {
            const file = e.target.files[0];
            const preview = $('#sliderPreview');

            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtension = /\.(jpg|jpeg|png)$/i;

                if (!validExtension.test(fileName)) {
                    alert('Only .jpg, .jpeg, or .png files are allowed.');
                    $(this).val('');
                    preview.addClass('d-none');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.attr('src', event.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                preview.addClass('d-none').attr('src', '');
            }
        });
    </script>
@endsection
