@extends('layouts.app')

@section('content')
<div class="container">
    <div class="title">
	    <h1 class="text-dark float-left">Category</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
    	    <h2>Edit</h2>
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="category_name" class="form-control @error('category_name') is-invalid @enderror"
                        value="{{ old('category_name', $category->category_name) }}">
                    @error('category_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Parent Category</label>
                    <div class="custom-select-wrapper">
                        <select name="parent_id" class="form-control custom-select-box">
                            <option value="">Select</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Category Banner (JPG)</label>
                    <input type="file" name="banner" id="banner" class="form-control" accept=".jpg">

                    @if($category->banner)
                        <img id="bannerPreview" src="{{ asset('storage/'.$category->banner) }}" width="100" class="mt-2 rounded">
                    @else
                        <img id="bannerPreview" src="#" width="100" class="mt-2 rounded d-none">
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Subcategory Image (Small)</label>
                    <input type="file" name="subcategory_image_small" id="subcategory_image_small" class="form-control" accept=".jpg,.jpeg,.png">
                   
                    @if($category->subcategory_image_small)
                        <img id="SubCatSmallPreview" src="{{ asset('storage/'.$category->subcategory_image_small) }}" width="100" class="mt-2 rounded">
                    @else
                        <img id="SubCatSmallPreview" src="#" width="100" class="mt-2 rounded d-none">
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Subcategory Image (Large)</label>
                    <input type="file" name="subcategory_image_large" id="subcategory_image_large" class="form-control" accept=".jpg,.jpeg,.png">

                    @if($category->subcategory_image_large)
                        <img id="SubCatLargePreview" src="{{ asset('storage/'.$category->subcategory_image_large) }}" width="100" class="mt-2 rounded">
                    @else
                        <img id="SubCatLargePreview" src="#" width="100" class="mt-2 rounded d-none">
                    @endif
                </div>

                <div class="form-group">
                    <label class="text-left">Sort Order</label>
                    <div class="">
                        <input type="text" name="sort_order" class="form-control" placeholder="Sort Order" value="{{ old('sort_order', $category->sort_order) }}">
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-left d-block mb-1">Status</label>
                            <div class="d-flex align-items-center py-2">
                                <label class="mr-4 mb-0">
                                <input type="radio" name="is_active" value="1" 
                                {{ old('is_active', $category->is_active) == 1 ? 'checked' : '' }}> Active
                                </label>
                                <label class="mb-0">
                                <input type="radio" name="is_active" value="0" 
                                {{ old('is_active', $category->is_active) == 0 ? 'checked' : '' }}> Inactive
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Show on Homepage -->
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <div class="form-check">
                                <input type="hidden" name="show_on_homepage" value="0">
                                <input type="checkbox" name="show_on_homepage" class="form-check-input" id="show_home" value="1" {{ old('show_on_homepage', $category->show_on_homepage) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_home">Show on Homepage</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $category->meta_title) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control">{{ old('meta_description', $category->meta_description) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">SEO Image</label>
                    <input type="file" name="seo_image" id="seo_image" class="form-control" accept=".jpg,.jpeg,.png">

                    @if($category->seo_image)
                        <img id="seoPreview" src="{{ asset('storage/'.$category->seo_image) }}" width="100" class="mt-2 rounded">
                    @else
                        <img id="seoPreview" src="#" width="100" class="mt-2 rounded d-none">
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <textarea name="meta_tags" class="form-control">{{ old('meta_tags', $category->meta_tags) }}</textarea>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript"> 
    
    $(document).ready(function() {
        $('#banner').change(function(e) {
           
            const file = e.target.files[0];
            const preview = $('#bannerPreview');

            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtension = /\.jpg$/i;

                // Check if file extension is .jpg
                if (!validExtension.test(fileName)) {
                    alert('Only .jpg files are allowed.');
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

        // Preview for SEO Image
        $('#seo_image').change(function(e) {
            const file = e.target.files[0];
            const preview = $('#seoPreview');

            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtension = /\.(jpg|jpeg|png)$/i;

                if (!validExtension.test(fileName)) {
                    alert('Only .jpg, .jpeg, or .png files are allowed for the SEO image.');
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

        // Preview for Subcategory Small/Large Image
        $('#subcategory_image_small, #subcategory_image_large').change(function (e) 
        {
            const file = e.target.files[0];
            const inputId = $(this).attr('id');  

            let preview =
                inputId === 'subcategory_image_small'
                    ? $('#SubCatSmallPreview')
                    : $('#SubCatLargePreview');

            if (file) {
                const fileName = file.name.toLowerCase();
                const validExtension = /\.(jpg|jpeg|png)$/i;

                if (!validExtension.test(fileName)) {
                    alert('Only .jpg, .jpeg, or .png files are allowed');

                    $(this).val('');
                    preview.addClass('d-none');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.attr('src', event.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(file);

            } else {
                preview.addClass('d-none').attr('src', '');
            }
        });

    });
    CKEDITOR.replace('description', {
        height: 200,
        removeButtons: 'PasteFromWord'
    });
    
</script>
@endsection
