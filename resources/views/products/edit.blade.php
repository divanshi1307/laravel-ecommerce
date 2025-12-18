@extends('layouts.app')

@section('content')
<div class="container">

    <div class="title">
	    <h1 class="text-dark float-left">Products</h1>
        <div class="clearfix"></div>
    </div>
    
    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
        <h2>Edit</h2>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-sm-2 float-right text-right mb-4">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Product Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $product->title) }}">
                    
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}">

                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Item Code</label>
                    <input type="text" name="product_item_code" class="form-control" value="{{ $product->product_item_code }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Select Category <span class="text-danger">*</span></label>
                    <div class="custom-select-wrapper position-relative">
                        <select name="category_id" id="category_id" 
                                class="form-control custom-select-box @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}> {{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>

                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Select Sub Category </label>
                    <div class="custom-select-wrapper position-relative">
                        <select name="subcategory_id" id="subcategory_id" class="form-control custom-select-box">
                            <option value="">-- Select Subcategory --</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ $sub->id == $product->subcategory_id ? 'selected' : '' }}>
                                    {{ $sub->category_name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Select Brand</label>
                    <div class="custom-select-wrapper position-relative">
                        <select name="brand_id" class="form-control custom-select-box">
                            <option value="">-- Select --</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" {{ $b->id == $product->brand_id ? 'selected':'' }}>
                                    {{ $b->brand_name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>GST%</label>
                    <div class="custom-select-wrapper position-relative">
                        <select name="gst_id" class="form-control custom-select-box">
                            <option value="">-- Select GST% --</option>
                            @foreach($gst as $g)
                                <option value="{{ $g->id }}" {{ $g->id == $product->gst_id ? 'selected':'' }}>
                                    {{ $g->gst_percentage }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Product Type <span class="text-danger">*</span></label>
                    <div class="custom-select-wrapper">
                        <select name="product_type" id="product_type" class="form-control custom-select-box @error('product_type') is-invalid @enderror" required>
                            <option value="">Select Product Type</option>
                            <option value="simple" {{ $product->product_type=='simple'?'selected':'' }}>Module For Simple Product</option>
                            <option value="variant" {{ $product->product_type=='variant'?'selected':'' }}>Module For Variant Product</option>
                            <option value="adult" {{ $product->product_type=='adult'?'selected':'' }}>Module For Adult Product</option>
                        </select>
                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>
                        @error('product_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Specifications -->
                <div class="col-md-12 mb-3">
                    <label>Specifications</label>
                    <table class="table table-bordered" id="specifications_table">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Value</th>
                                <th width="50">
                                    <button type="button" id="addSpecRow" class="btn btn-sm btn-success">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($specifications))
                                @foreach($specifications as $index => $spec)
                                    <tr>
                                        <td>
                                            <input type="text" name="specifications[{{ $index }}][label]" class="form-control" value="{{ $spec['label'] ?? '' }}" placeholder="Label">
                                        </td>
                                        <td>
                                            <input type="text" name="specifications[{{ $index }}][value]" class="form-control" value="{{ $spec['value'] ?? '' }}" placeholder="Value">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger removeSpecRow">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td><input type="text" name="specifications[0][label]" class="form-control" placeholder="Label"></td>
                                    <td><input type="text" name="specifications[0][value]" class="form-control" placeholder="Value"></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger removeSpecRow">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="price_section">
                <div class="row">  
                    <div class="col-md-6 mb-3">
                        <label>Price <span class="text-danger">*</span></label>
                        <input type="number" name="price" placeholder="Price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ rtrim(rtrim($product->price, '0'), '.') }}">

                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Special Price</label>
                        <input type="number" name="special_price" placeholder="Special Price" class="form-control" value="{{ rtrim(rtrim($product->special_price, '0'), '.') }}">
                    </div>
                </div>
            </div>
        
            <div class="row" id="stock_section">
                <div class="col-md-6 mb-3">
                    <label>Stock Qty <span class="text-danger">*</span></label>
                    <input type="number" name="stock_quantity" id="stock_quantity"
                        class="form-control @error('stock_quantity') is-invalid @enderror"
                        value="{{ old('stock_quantity', $product->stock_quantity) }}">

                    @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Stock Status</label>
                    <div class="custom-select-wrapper">
                        <select name="stock_status" class="form-control custom-select-box" id="stock_status">
                            <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- <div id="variant_button_section" class="mb-3" style="display:none;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#variantModal">
                    + Update Variant
                </button>
            </div> --}}

            <!-- Attribute Section -->
           <div id="attribute_section">
                @foreach($attributes as $index => $group)
                    <div class="attribute_row mb-3">

                        <div class="d-flex align-items-end attribute_group" data-index="{{ $index }}" style="gap:8px;">
                            <div style="flex: 1;">
                                <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                                <input type="text" name="attribute_name[{{ $index }}]" value="{{ $group['attribute_name'] }}"
                                    class="form-control">
                            </div>

                            <button type="button" class="btn btn-success btn-sm add_attribute_name_row"><i class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-sm remove_attribute_name_row"><i class="fa fa-trash"></i></button>
                        </div>

                        <div class="attribute_values_container">
                            @foreach($group['rows'] as $rowIndex => $row)
                                <div class="d-flex flex-wrap align-items-end gap-3 attribute_type">

                                    <div class="col-md-3 baby_weight_section">
                                        <label class="form-label">Baby Weight</label>
                                        <select name="baby_weight_id[{{ $index }}][]" class="form-control custom-select-box">
                                            <option value="">-- Select Baby Weight--</option>
                                            @foreach($baby_weight as $weight)
                                                <option value="{{ $weight->id }}" 
                                                    {{ isset($row['baby_weight_id']) && $row['baby_weight_id'] == $weight->id ? 'selected' : '' }}>
                                                    {{ $weight->weight_range }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 age_group_section">
                                        <label class="form-label">Age Groups</label>
                                        <select name="age_group_id[{{ $index }}][]" class="form-control custom-select-box">
                                            <option value="">-- Select Age Groups--</option>
                                            @foreach($age_group as $ag)
                                                <option value="{{ $ag->id }}" 
                                                    {{ isset($row['age_group_id']) && $row['age_group_id'] == $ag->id ? 'selected' : '' }}>
                                                    {{ $ag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 adult_waist_section">
                                        <label class="form-label">Adult Waist</label>
                                        <select name="adult_waist_id[{{ $index }}][]" class="form-control custom-select-box">
                                            <option value="">-- Select Adult Waist--</option>
                                            @foreach($adult_waist as $waist)
                                                <option value="{{ $waist->id }}" 
                                                    {{ isset($row['adult_waist_id']) && $row['adult_waist_id'] == $waist->id ? 'selected' : '' }}>
                                                    {{ $waist->waist_size }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Attribute Value <span class="text-danger">*</span></label>
                                        <input type="text" name="attribute_value[{{ $index }}][]" class="form-control" placeholder="Quantity in Piece" value="{{ $row['attribute_value'] }}">
                                    </div>

                                    <div class="d-flex align-items-end" style="gap:8px;">
                                        <button type="button" class="btn btn-success btn-sm add_attribute_row"><i class="fa fa-plus"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm remove_attribute_row"><i class="fa fa-trash"></i></button>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Variant Combinations -->

            @if($product->product_type == 'variant' || $product->product_type == 'adult')
                <div id="variant_wrapper">
                    <hr>
                    <h5 class="mt-4 mb-3">Variant Combinations</h5>

                    <div id="variant_section">
                        @if($product->attributeRelations->count() > 0)
                            @foreach($product->attributeRelations as $i => $row)
                                @php
                                    $json = json_decode($row->json, true);   
                                    $combination = $row->value;              
                                @endphp

                                <div class="variant_row border rounded p-3 mb-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <label>Value Combination</label>
                                            <input type="text" name="value[]" 
                                                value="{{ $combination }}" 
                                                class="form-control" readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label>MRP / Original Price</label>
                                            <input type="number" name="original_price[]" 
                                                value="{{ $row->original_price ?? '' }}"
                                                class="form-control" placeholder="MRP">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Price</label>
                                            <input type="number" name="price[]" 
                                                value="{{ $row->price ?? '' }}"
                                                class="form-control" placeholder="price">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Stock Quantity</label>
                                            <input type="number" name="quantity[]" 
                                                value="{{ $row->quantity ?? '' }}"
                                                class="form-control" placeholder="quantity">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Images</label>
                                            <input type="file" name="image[{{ $i }}][]" class="form-control new-image-input" data-index="{{ $i }}" multiple>

                                            @php
                                                $imageIds = !empty($row->image) ? explode('|', $row->image) : [];
                                            @endphp

                                            <div class="existing-images mt-2 d-flex flex-wrap gap-2">
                                                @foreach($imageIds as $imgId)
                                                    @php $img = \App\Models\Upload::find($imgId); @endphp
                                                    @if($img)
                                                        <div class="image-thumb" style="position: relative; width: 70px;">
                                                            <img src="{{ asset('uploads/products/' . $img->file_name) }}"
                                                                style="width: 70px; height: 70px; object-fit: cover;"
                                                                class="img-thumbnail">
                                                            <span class="badge bg-danger remove-existing-image"
                                                                data-image-id="{{ $imgId }}"
                                                                data-row-id="{{ $row->id }}"
                                                                style="position:absolute; top:-5px; right:-5px; cursor:pointer;">X</span>
                                                            <input type="hidden" name="variant_old_images[{{ $i }}][]" value="{{ $imgId }}">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                            <div class="new-preview-images mt-2 d-flex flex-wrap gap-2" data-preview="{{ $i }}"></div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>
                                                <input type="radio" name="is_default[]" 
                                                    value="{{ $combination }}"
                                                    {{ $row->is_default ? 'checked' : '' }}>
                                                Default
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No Variant Combinations Found.</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Highlights</label>
                    <textarea name="highlights" class="form-control" placeholder="Highlights" rows="3">{{ $product->highlights }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Sort No</label>
                    <input type="number" name="sort_no"  placeholder="Sort No" class="form-control" value="{{ $product->sort_no }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <div class="custom-select-wrapper">
                        <select name="is_active" class="form-control custom-select-box">
                            <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Images preview -->
                <div class="col-md-12 mb-3">
                    <label>Product Images<span class="text-danger">*</span>(Multiple)</label>
                    <input type="file" name="images[]" id="product_images" class="form-control" multiple>
                    @if ($errors->has('images'))
                        <span class="text-danger">{{ $errors->first('images') }}</span>
                    @endif
                    @if ($errors->has('images.*'))
                        <span class="text-danger">{{ $errors->first('images.*') }}</span>
                    @endif
                </div>

                <div class="col-md-12 mb-3" id="existing-images-container">
                    <div id="existing-images" class="d-flex flex-wrap">
                        @if($product->images_list && $product->images_list->count() > 0)
                            @foreach($product->images_list as $img)
                                <div class="position-relative me-2 mb-2 image-preview" style="display:inline-block;">
                                    <img src="{{ asset('uploads/products/' . $img->file_name) }}" width="80" style="border:1px solid #ccc; padding:2px;">
                                    <button type="button" class="btn btn-sm btn-danger remove-image-btn"
                                        data-id="{{ $img->id }}" style="position:absolute; top:0; right:0; padding:2px 6px; font-size:12px;">&times;</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Bottom Images preview -->
                <div class="col-md-12 mb-3">
                    <label>Bottom Images</label>
                    <input type="file" name="bottom_images[]" id="bottom_images" class="form-control" multiple>
                </div>

                <div class="col-md-12 mb-3" id="existing-bottom-images-container">
                    <div id="existing-bottom-images" class="d-flex flex-wrap">
                        @if($product->bottom_images_list && $product->bottom_images_list->count() > 0)
                            @foreach($product->bottom_images_list as $img)
                                <div class="position-relative me-2 mb-2 image-preview" style="display:inline-block;">
                                    <img src="{{ asset('uploads/products/' . $img->file_name) }}" width="80" style="border:1px solid #ccc; padding:2px;">
                                    <button type="button" class="btn btn-sm btn-danger remove-bottom-image-btn"
                                        data-id="{{ $img->id }}" style="position:absolute; top:0; right:0; padding:2px 6px; font-size:12px;">&times;</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tags</label>
                    <input type="text" name="tags" placeholder="Tags" class="form-control" value="{{ $product->tags }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Manufacturing Date</label>
                    <input type="date" name="manufacture_date" class="form-control" value="{{ old('manufacture_date', $product->manufacture_date?->format('Y-m-d')) }}" placeholder="Manufacturing Date">
                </div>

                <!-- SEO -->
                <div class="col-md-12 mb-3">
                    <hr><h4>SEO Section</h4>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" placeholder="Meta Title" class="form-control" value="{{ $product->meta_title }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description" placeholder="Meta Description" class="form-control">{{ $product->meta_description }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">SEO Image</label>
                    <input type="file" name="seo_image" id="seo_image" class="form-control" accept=".jpg,.jpeg,.png">

                    <img id="seoPreview" 
                        src="{{ $product->seoImage ? asset('uploads/seo/' . $product->seoImage->file_name) : '#' }}" 
                        width="100" 
                        class="mt-2 rounded {{ $product->seoImage ? '' : 'd-none' }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Meta Keywords</label>
                    <input type="text" name="meta_tags" placeholder="Meta Keywords" class="form-control" value="{{ $product->meta_tags }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Meta Tags</label>
                    <textarea name="meta_snippet" id="meta_snippet" placeholder="Meta Tags" class="form-control">{{ $product->meta_snippet }}</textarea>
                </div>
            </div>

            <!-- Variant Modal -->
            <div class="modal fade" id="variantModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Product Variants</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="col-md-12 mb-3">
                            <label>Variant Name</label>
                            <input type="text" name="variant_name" class="form-control" placeholder="e.g., Color, Size">
                        </div>

                        <table class="table table-bordered" id="variant_options_table">
                            <thead>
                                <tr>
                                    <label>Variant Options</label>
                                    <th>Option Name</th>
                                    <th>Option Price</th>
                                    <th>Product Image</th>
                                    <th><button type="button" class="btn btn-success btn-sm" id="addRow">+</button></th>
                                    <input type="hidden" name="deleted_variant_images" id="deleted_variant_images">
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="variant_option[]" class="form-control" placeholder="e.g., Red"></td>
                                    <td><input type="number" name="variant_price[]" class="form-control" placeholder="Price"></td>
                                    <td>
                                        <input type="file" name="variant_images[0][]" class="form-control" multiple>
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="updateVariant">Update Variant</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    CKEDITOR.replace('description');
    $(function() {
        let specIndex = {{ !empty($specifications) ? count($specifications) : 1 }};
        $('#addSpecRow').click(function () {
            let newRow = `
                <tr>
                    <td><input type="text" name="specifications[${specIndex}][label]" class="form-control" placeholder="Label"></td>
                    <td><input type="text" name="specifications[${specIndex}][value]" class="form-control" placeholder="Value"></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger removeSpecRow">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#specifications_table tbody').append(newRow);
            specIndex++;
        });

        $(document).on('click', '.removeSpecRow', function () {
            $(this).closest('tr').remove();
        });
    });

    $(document).ready(function() {
        $('#product_images').on('change', function() {
            var files = this.files;

            $.each(files, function(index, file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var html = `
                    <div class="position-relative me-2 mb-2 image-preview" style="display:inline-block;">
                        <img src="${e.target.result}" width="80" style="border:1px solid #ccc; padding:2px;">
                        <button type="button" class="btn btn-sm btn-danger remove-new-image-btn"
                            style="position:absolute; top:0; right:0; padding:2px 6px; font-size:12px;">&times;</button>
                    </div>
                    `;
                    $('#existing-images').append(html);
                }
                reader.readAsDataURL(file);
            });
        });

        $(document).on('click', '.remove-new-image-btn', function() {
            $(this).parent().remove();
        });

        $('#updateVariant').on('click', function () {
            $('#variantModal').modal('hide');
        });

        $('#existing-images').on('click', '.remove-image-btn', function() {
            var button = $(this);
            var imageId = button.data('id');

            if(confirm('Are you sure you want to remove this image?')) {
                $.ajax({
                    url: '/products/remove-image/' + imageId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            button.parent().remove(); 
                            if($('#existing-images').children().length === 0) {
                                $('#existing-images-container').remove(); 
                            }
                        } else {
                            alert('Failed to delete image.');
                        }
                    },
                    error: function() {
                        alert('Something went wrong.');
                    }
                });
            }
        });

        // PREVIEW FOR BOTTOM IMAGES
        $('#bottom_images').on('change', function (event) {
            $('#existing-bottom-images').append('<div id="new-bottom-preview" class="d-flex flex-wrap"></div>');
            let files = event.target.files;
            let fileArray = Array.from(files);
            let input = this;

            fileArray.forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let html = `
                        <div class="position-relative me-2 mb-2 image-preview" data-index="${index}" style="display:inline-block;">
                            <img src="${e.target.result}" width="80" style="border:1px solid #ccc; padding:2px;">
                            <button type="button" class="btn btn-sm btn-danger remove-new-image-btn"
                                data-index="${index}"
                                style="position:absolute; top:0; right:0; padding:2px 6px; font-size:12px;">&times;</button>
                        </div>
                    `;
                    $('#new-bottom-preview').append(html);
                };
                reader.readAsDataURL(file);
            });
        });

        $('#existing-bottom-images').on('click', '.remove-bottom-image-btn', function() {
            var button = $(this);
            var imageId = button.data('id');

            if(confirm('Are you sure you want to remove this image?')) {
                $.ajax({
                    url: '/products/remove-bottom-image/' + imageId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            button.parent().remove(); 
                            if($('#existing-bottom-images').children().length === 0) {
                                $('#existing-bottom-images-container').remove(); 
                            }
                        } else {
                            alert('Failed to delete image.');
                        }
                    },
                    error: function() {
                        alert('Something went wrong.');
                    }
                });
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

        // Show / Hide Price and Variant Section
        function toggleProductType() {
            let type = $('#product_type').val();
            if (type === 'variant') {
                $('input[name="price"]').closest('.col-md-6').hide();
                $('input[name="special_price"]').closest('.col-md-6').hide();
                $('#stock_section').hide();
                $('.adult_waist_section').hide();
                $('#variant_button_section').show();
                $('#attribute_section').show();
                $('#variant_wrapper').show();
                $('.baby_weight_section').show();
                $('.age_group_section').show();

            } else if (type === 'adult') {
                $('#price_section').hide();
                $('#stock_section').hide();
                $('#variant_button_section').show();
                $('#attribute_section').show();
                $('.baby_weight_section').hide();
                $('.age_group_section').hide();
                $('.adult_waist_section').show();

            }else {
                $('input[name="price"]').closest('.col-md-6').show();
                $('input[name="special_price"]').closest('.col-md-6').show();
                $('#stock_section').show();
                $('#stock_status').show();
                $('#variant_button_section').hide();
                $('#attribute_section').hide();
                $('#variant_wrapper').hide();
            }
        }

        toggleProductType();
        $('#product_type').on('change', toggleProductType);

        // Variant filled values
        var existingVariants = {!! json_encode($product->variants) !!};
        $('#variantModal').on('shown.bs.modal', function () {
            $('#variant_options_table tbody').html('');

            if (existingVariants.length > 0) {
                $('input[name="variant_name"]').val(existingVariants[0].variant_name);

                existingVariants.forEach(function (item, index) {
                    let imagesHtml = '';
                    let basePath = "{{ asset('uploads/variant_images') }}/";

                    if (item.images && item.images.length > 0) {
                        item.images.forEach(function(img) {
                            imagesHtml += `
                                <div class="variant-image-wrapper" style="display:inline-block; position:relative; margin-right:5px;">
                                    <img src="${basePath}${img.file_name}" class="img-thumbnail" width="60" height="60">
                                    <button type="button" class="btn btn-danger btn-xs removeExistingImage" data-image-id="${img.id}" style="position:absolute; top:-5px; right:-5px; padding:1px 4px; line-height:1;">×</button>
                                </div>`;
                        });
                    }
                    $('#variant_options_table tbody').append(`
                        <tr>
                            <input type="hidden" name="variant_id[]" value="${item.id}">
                            <td><input type="text" name="variant_option[]" class="form-control" value="${item.variant_option ?? ''}"></td>
                            <td><input type="number" name="variant_price[]" class="form-control" value="${item.variant_price ?? ''}"></td>
                            <td>
                                ${imagesHtml}
                                <input type="file" name="variant_images[${index}][]" class="form-control mt-2" multiple>
                            </td>
                            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                        </tr>
                    `);
                });
            } else {
                $('#variant_options_table tbody').append(`
                    <tr>
                        <input type="hidden" name="variant_id[]" value="">
                        <td><input type="text" name="variant_option[]" class="form-control"></td>
                        <td><input type="number" name="variant_price[]" class="form-control"></td>
                        <td><input type="file" name="variant_images[0][]" class="form-control" multiple></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                    </tr>
                `);
            }
        });

        // Add Row
        $(document).on('click', '#addRow', function () {
            let index = $('#variant_options_table tbody tr').length;

            $('#variant_options_table tbody').append(`
                <tr>
                    <input type="hidden" name="variant_id[]" value="">
                    <td><input type="text" name="variant_option[]" class="form-control"></td>
                    <td><input type="number" name="variant_price[]" class="form-control"></td>
                    <td><input type="file" name="variant_images[${index}][]" class="form-control" multiple></td>
                    <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                </tr>
            `);
        });

        // Remove Row
        $(document).on('click', '.removeRow', function () {
            let row = $(this).closest('tr');
            let variantId = row.find('input[name="variant_id[]"]').val();

            if (variantId) {
                $('#variant_options_table').append(`
                    <input type="hidden" name="delete_variant_ids[]" value="${variantId}">
                `);
            }
            row.remove();
        });

         // Delete Variant Image
        $(document).on('click', '.removeExistingImage', function () {
            let button = $(this);
            let imageId = button.data('image-id');

            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: '/variant-image-delete/' + imageId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            button.closest('.variant-image-wrapper').remove();
                        } else {
                            alert(response.message || 'Unable to delete image.');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error deleting image.');
                    }
                });
            }
        });

        $(document).on('change', 'input[type="file"][name^="variant_images"]', function () {
            var $input = $(this);
            var files = this.files;

            var $previewContainer = $('<div class="new-preview mt-2"></div>');
            $input.before($previewContainer);

            $.each(files, function (i, file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var $imgWrapper = $('<div class="variant-image-wrapper" style="display:inline-block; position:relative; margin:3px;"></div>');
                    var $img = $('<img>', {
                        src: e.target.result,
                        class: 'img-thumbnail',
                        width: 60,
                        height: 60
                    });
                    var $removeBtn = $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-xs removeNewPreview',
                        html: '×',
                        css: {
                            position: 'absolute',
                            top: '-5px',
                            right: '-5px',
                            padding: '1px 4px',
                            lineHeight: '1'
                        }
                    });

                    $imgWrapper.append($img).append($removeBtn);
                    $previewContainer.append($imgWrapper);
                };
                reader.readAsDataURL(file);
            });

            var $newInput = $input.clone().val(''); 
            $input.after($newInput);                
            $input.hide();                        
        });

        // Remove preview before upload (for new images)
        $(document).on('click', '.removeNewPreview', function () {
            var $wrapper = $(this).closest('.variant-image-wrapper');
            var $input = $wrapper.closest('.new-preview').nextAll('input[type="file"]').first();
            $wrapper.remove();
        });

        $('#stock_quantity').on('input', function() {
            let qty = parseInt($(this).val()) || 0; 
            let $status = $('#stock_status');

            $status.empty(); 

            if (qty > 0) {
                $status.append('<option value="in_stock" selected>In Stock</option>');
            } else {
                $status.append('<option value="out_of_stock" selected>Out of Stock</option>');
            }
        });

        // ADD ATTRIBUTE NAME ROW 

        function reindexGroups() {
            $(".attribute_group").each(function(groupIndex) {
                $(this).attr("data-index", groupIndex);

                // Update attribute_name input
                $(this).find("input[name^='attribute_name']").attr("name", `attribute_name[${groupIndex}]`);

                // Update all rows inside this group
                $(this).closest(".attribute_row").find(".attribute_type").each(function() {
                    $(this).find("input[name^='attribute_value']").attr("name", `attribute_value[${groupIndex}][]`);
                    $(this).find("input[name^='baby_weight_id']").attr("name", `baby_weight_id[${groupIndex}][]`);
                    $(this).find("select[name^='age_group_id']").attr("name", `age_group_id[${groupIndex}][]`);
                });
            });
        }

        // Add attribute value row
        $(document).on("click", ".add_attribute_row", function() {
            let group = $(this).closest(".attribute_row").find(".attribute_values_container");
            let clone = group.find(".attribute_type:first").clone();

            clone.find("input, select").val(""); // clear inputs
            group.append(clone);

            reindexGroups();
        });

        // Remove attribute value row
        $(document).on("click", ".remove_attribute_row", function() {
            let group = $(this).closest(".attribute_row").find(".attribute_values_container");
            if (group.find(".attribute_type").length > 1) {
                $(this).closest(".attribute_type").remove();
                reindexGroups();
            }
        });

        // Add attribute group
        $(document).on("click", ".add_attribute_name_row", function() {
            let lastGroup = $(".attribute_row:last").clone();
            lastGroup.find("input, select").val(""); // clear values
            $("#attribute_section").append(lastGroup);
            reindexGroups();
        });

        // Remove attribute group
        $(document).on("click", ".remove_attribute_name_row", function() {
            if ($(".attribute_row").length > 1) {
                $(this).closest(".attribute_row").remove();
                reindexGroups();
            } else {
                alert("At least one attribute group is required");
            }
        });


        // Focus input when clicking box
        $(document).on('click', '.tag-box', function () {
            $(this).find('.tag-input').focus();
        });

        // Add tag on Enter
        $(document).on('keydown', '.tag-input', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                let value = $(this).val().trim();

                if (value !== '') {
                    let box = $(this).closest('.tag-box');
                    let input = box.find('.tag-input');

                    input.before(`
                        <span class="tag">${value} <i class="fa fa-times remove-tag"></i></span>
                    `);

                    $(this).val('');
                    updateTagCSV(box);
                }
            }
        });

        // Remove tag
        $(document).on('click', '.remove-tag', function () {
            let box = $(this).closest('.tag-box');
            $(this).parent('.tag').remove();
            updateTagCSV(box);
        });

        // Update hidden CSV value
        function updateTagCSV(box) {
            let name = box.data('name');
            let values = [];

            box.find('.tag').each(function () {
                let text = $(this).clone().children().remove().end().text().trim();
                values.push(text);
            });

            box.find('input[type="hidden"]').val(values.join(','));

            if (values.length === 0) {
                box.addClass('is-invalid');
            } else {
                box.removeClass('is-invalid');
            }
        }

        $(document).on("click", ".remove-existing-image", function () {
            let btn = $(this);
            let imageId = btn.data("image-id");
            let rowId   = btn.data("row-id");

            $.ajax({
                url: "{{ route('variant-combination.removeImage') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    image_id: imageId,
                    row_id: rowId
                },
                success: function (res) {
                    if (res.success) {
                        btn.closest(".image-thumb").fadeOut(500, function(){
                            $(this).remove();
                        });
                    } else {
                        btn.text("X");
                        alert("Failed to remove image");
                    }
                },
                error: function () {
                    btn.text("X");
                    alert("Error removing image");
                }
            });
        });

        ///new image inserted in variant combination
        $(document).on("change", ".new-image-input", function () {
            const input = this;
            const index = $(this).data("index");
            const previewBox = $(`.new-preview-images[data-preview="${index}"]`);

            if (!input.files || !input.files.length) return;

            [...input.files].forEach((file, i) => {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const html = `
                        <div class="image-thumb" style="position: relative; width: 70px; margin-right:6px;">
                            <img src="${e.target.result}"
                                style="width: 70px; height: 70px; object-fit: cover;"
                                class="img-thumbnail">
                            <span class="badge bg-danger remove-new-image"
                                data-file-index="${i}"
                                data-input-index="${index}"
                                style="position:absolute; top:-5px; right:-5px; cursor:pointer;">X</span>
                        </div>
                    `;
                    previewBox.append(html);
                };

                reader.readAsDataURL(file);
            });
        });

        $(document).on("click", ".remove-new-image", function () {
            const btn = $(this);
            const fileIndex = Number(btn.data("file-index"));
            const inputIndex = btn.data("input-index");
            const input = $(`.new-image-input[data-index="${inputIndex}"]`)[0];

            btn.closest(".image-thumb").remove();

            if (!input) return;

            const dt = new DataTransfer();
            [...input.files].forEach((f, idx) => {
                if (idx !== fileIndex) dt.items.add(f);
            });

            input.files = dt.files;
            const previewBox = $(`.new-preview-images[data-preview="${inputIndex}"]`);
            previewBox.empty();
            [...input.files].forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewBox.append(`
                        <div class="image-thumb" style="position: relative; width: 70px; margin-right:6px;">
                            <img src="${e.target.result}"
                                style="width: 70px; height: 70px; object-fit: cover;"
                                class="img-thumbnail">
                            <span class="badge bg-danger remove-new-image"
                                data-file-index="${i}"
                                data-input-index="${inputIndex}"
                                style="position:absolute; top:-5px; right:-5px; cursor:pointer;">X</span>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            });
        });

    });
</script>
@endsection
