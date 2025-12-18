@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
	    <h1 class="text-dark float-left">Brand</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
    	    <h2>Edit</h2>
    
            <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="brand_name" class="form-control @error('brand_name') is-invalid @enderror"
                        value="{{ old('brand_name', $brand->brand_name) }}">
                    @error('brand_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $brand->slug) }}" placeholder="Slug">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Brand Logo</label>
                    <input type="file" name="brand_logo" id="brand_logo" class="form-control" accept=".jpg,.jpeg,.png">

                    @if($brand->brand_logo)
                        <img id="brandLogoPreview" src="{{ asset('storage/'.$brand->brand_logo) }}" width="100" class="mt-2 rounded">
                    @else
                        <img id="brandLogoPreview" src="#" width="100" class="mt-2 rounded d-none">
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ $brand->sort_order }}">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-left d-block mb-1">Status</label>
                            <div class="d-flex align-items-center py-2">
                                <label class="mr-4 mb-0">
                                <input type="radio" name="is_active" value="1" 
                                {{ old('is_active', $brand->is_active) == 1 ? 'checked' : '' }}> Active
                                </label>
                                <label class="mb-0">
                                <input type="radio" name="is_active" value="0" 
                                {{ old('is_active', $brand->is_active) == 0 ? 'checked' : '' }}> Inactive
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Show on Homepage -->
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <div class="form-check">
                                <input type="hidden" name="show_on_homepage" value="0">
                                <input type="checkbox" name="show_on_homepage" class="form-check-input" id="show_home" value="1" {{ old('show_on_homepage', $brand->show_on_homepage) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_home">Show on Homepage</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ $brand->description }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ $brand->meta_title }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control">{{ $brand->meta_description }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <textarea name="meta_tags" class="form-control">{{ old('meta_tags', $brand->meta_tags) }}</textarea>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    CKEDITOR.replace('description');

    $(document).ready(function() {
        $('#brand_logo').change(function(e) {
            const file = e.target.files[0];
            const preview = $('#brandLogoPreview');

            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtension = /\.(jpg|jpeg|png)$/i;

                if (!validExtension.test(fileName)) {
                    alert('Only .jpg, .jpeg, or .png files are allowed.');
                    $(this).val('');
                    preview.addClass('d-none');
                    return;
                }

                // Read file and show preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.attr('src', event.target.result);
                    preview.removeClass('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                preview.addClass('d-none');
                preview.attr('src', '');
            }
        });
    });
</script>
@endsection
