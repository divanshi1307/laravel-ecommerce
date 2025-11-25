@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
	    <h1 class="text-dark float-left">Brand</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
    	    <h2>Add</h2>
            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="brand_name" 
                            class="form-control @error('brand_name') is-invalid @enderror"
                            value="{{ old('brand_name') }}">
                    @error('brand_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="clearfix"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Brand Logo</label>
                    <input type="file" name="brand_logo" class="form-control" accept=".jpg,.jpeg,.png">
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="text" name="sort_order" class="form-control" placeholder="Sort Order" value="{{ old('sort_order', 0) }}">
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
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="{{ old('meta_title') }}">
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" placeholder="Meta Description" class="form-control">{{ old('meta_description') }}</textarea>
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
</div>
@endsection

@section('scripts')
<script>
    CKEDITOR.replace('description');
</script>
@endsection
