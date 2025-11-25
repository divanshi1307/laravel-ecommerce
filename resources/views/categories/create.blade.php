
@extends('layouts.app')

@section('content')

<div class="container ">
    <div class="title">
	    <h1 class="text-dark float-left">Category</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
    	    <h2>Add</h2>
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="category_name" 
                           class="form-control @error('category_name') is-invalid @enderror"
                           value="{{ old('category_name') }}" placeholder="Category Name">
                    @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Parent Category</label>
                    <div class="custom-select-wrapper">
                        <select name="parent_id" class="form-control custom-select-box">
                            <option value="">Select</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Category Banner</label>
                    <input type="file" name="banner" class="form-control" accept=".jpg">
                    @error('banner')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Subcategory Image (Small)</label>
                    <input type="file" name="subcategory_image_small" class="form-control" accept=".jpg,.jpeg,.png">
                    @error('subcategory_image_small')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Subcategory Image (Large)</label>
                    <input type="file" name="subcategory_image_large" class="form-control" accept=".jpg,.jpeg,.png">
                    @error('subcategory_image_large')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="text-left">Sort Order</label>
                    <div class="">
                        <input type="text" name="sort_order" class="form-control" placeholder="Sort Order" value="{{ old('sort_order', 0) }}" >
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-left d-block mb-1">Status</label>
                            <div class="d-flex align-items-center py-2">
                                <label class="mr-4 mb-0">
                                    <input type="radio" name="is_active" value="1" checked> Active
                                </label>
                                <label class="mb-0">
                                    <input type="radio" name="is_active" value="0"> Inactive
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Show on Homepage -->
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <div class="form-check">
                                <input type="hidden" name="show_on_homepage" value="0">
                                <input type="checkbox" name="show_on_homepage" class="form-check-input" id="show_home" value="1">
                                <label class="form-check-label" for="show_home">Show on Homepage</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="Meta Title">
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" placeholder="Meta Description" class="form-control">{{ old('meta_description') }}</textarea>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">SEO Image</label>
                    <input type="file" name="seo_image" class="form-control" accept=".jpg,.jpeg,.png">
                    @error('seo_image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <textarea name="meta_tags" placeholder="Meta Keywords" class="form-control">{{ old('meta_tags') }}</textarea>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    CKEDITOR.replace('description', {
        height: 200,
        removeButtons: 'PasteFromWord'
    });
</script>
@endsection
