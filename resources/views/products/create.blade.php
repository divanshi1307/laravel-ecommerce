@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="title">
	    <h1 class="text-dark float-left">Products</h1>
        <div class="clearfix"></div>
    </div>

    <div class="content">   
		<div class="loader"style="display:none;"><p><i class="fa fa-spinner fa-5x fa-spin"></i></p></div>
        <h2>Add</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="col-sm-2 float-right text-right mb-4">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
            </div>
            <div class="clearfix"></div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="Title">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Item Code</label>
                    <input type="text" name="product_item_code" value="{{ old('product_item_code') }}" class="form-control" placeholder="Item Code">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Select Category <span class="text-danger">*</span></label>

                    <div class="custom-select-wrapper position-relative">
                        <select name="category_id" id="category_id"
                                class="form-control custom-select-box @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>

                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>

                        @error('category_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Select Sub Category</label>
                    <div class="custom-select-wrapper position-relative">
                        <select name="subcategory_id" id="subcategory_id" class="form-control custom-select-box">
                            <option value="">-- Select Sub Category --</option>
                            @foreach($subcategories as $sub)
                                @if(old('category_id') == $sub->parent_id)
                                    <option value="{{ $sub->id }}"
                                        {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->category_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Select Brand</label>
                    <div class="custom-select-wrapper position-relative">
                        <select name="brand_id" class="form-control custom-select-box">
                            <option value="">-- Select Brand --</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}"
                                    {{ old('brand_id') == $b->id ? 'selected' : '' }}>
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
                                <option value="{{ $g->id }}"
                                    {{ old('gst_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->gst_percentage }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Product Type <span class="text-danger">*</span></label>
                    <div class="custom-select-wrapper position-relative">
                        <select name="product_type" id="product_type" class="form-control custom-select-box @error('product_type') is-invalid @enderror" required>   
                            <option value="">Select Product Type</option>
                            <option value="simple" {{ old('product_type') == 'simple' ? 'selected' : '' }}>Module For Simple Product</option>
                            <option value="variant" {{ old('product_type') == 'variant' ? 'selected' : '' }}>Module For Variant Product</option>
                        </select>

                        <i class="fa fa-caret-down select-icon" aria-hidden="true"></i>
                        @error('product_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Specifications Dynamic Table -->
                <div class="col-md-12 mb-3">
                    <label>Specifications</label>
                    <table class="table table-bordered" id="specifications_table">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Value</th>
                                <th width="50">
                                    <button type="button" class="btn btn-sm btn-success" id="addSpecRow">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="specifications[0][label]" class="form-control" placeholder="Label"></td>
                                <td><input type="text" name="specifications[0][value]" class="form-control" placeholder="Value"></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeSpecRow">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="price_section">
                <div class="row">  
                    <div class="col-md-6 mb-3">
                        <label>Price <span class="text-danger">*</span></label>
                        <input type="number" name="price" placeholder="Price"
                            class="form-control @error('price') is-invalid @enderror" required
                            value="{{ old('price') }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Special Price</label>
                        <input type="number" name="special_price" placeholder="Special Price" class="form-control" value="{{ old('special_price') }}">
                    </div>
                </div>
            </div>

            <div class="row" id="stock_section">
                <div class="col-md-6 mb-3">
                    <label>Stock Qty <span class="text-danger">*</span></label>
                    <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" required 
                    value="{{ old('stock_quantity') }}" id="stock_quantity" placeholder="Enter stock quantity">
                    @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Stock Status</label>
                    <div class="custom-select-wrapper">
                        <select name="stock_status" class="form-control custom-select-box" id="stock_status">
                            <option value="">Select</option>

                            <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>

                            <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                </div>
            </div>
  
            {{-- <div id="variant_button_section" class="mb-3" style="display:none;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#variantModal">
                    + Add Variant
                </button>
            </div> --}}

            <!-- Attribute Section -->
            <div id="attribute_section" style="display:none;">
                <div class="attribute_row mb-3">
                    <div class="d-flex align-items-end attribute_group" data-index="0" style="gap:8px;">
                        <div style="flex: 1;">
                            <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                            <input type="text" name="attribute_name[0]" class="form-control" placeholder="e.g. Color, Size" required>
                        </div>

                        <button type="button" class="btn btn-success btn-sm add_attribute_name_row"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-danger btn-sm remove_attribute_name_row"><i class="fa fa-trash"></i></button>
                    </div>

                    <div class="attribute_values_container">
                        <div class="d-flex flex-wrap align-items-end gap-3 attribute_type">
                            <div class="col-md-3">
                                <label class="form-label">Baby Weight</label>
                                <select name="baby_weight_id[0][]" class="form-control custom-select-box">
                                    <option value="">-- Select Baby Weight--</option>
                                    @foreach($baby_weight as $weight)
                                        <option value="{{ $weight->id }}">
                                            {{ $weight->weight_range }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Age Groups</label>
                                <select name="age_group_id[0][]" class="form-control custom-select-box">
                                    <option value="">-- Select Age Groups--</option>
                                    @foreach($age_group as $group)
                                        <option value="{{ $group->id }}">
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Attribute Value <span class="text-danger">*</span></label>
                                <input type="text" name="attribute_value[0][]" class="form-control" placeholder="e.g. Red, Large" required>
                            </div>

                            <div class="d-flex align-items-end" style="gap:8px;">
                                <button type="button" class="btn btn-success btn-sm add_attribute_row"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-danger btn-sm remove_attribute_row"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Highlights</label>
                    <textarea name="highlights" class="form-control" rows="3" placeholder="Enter product highlights...">{{ old('highlights') }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Sort No</label>
                    <input type="number" name="sort_no" class="form-control" value="{{ old('sort_order', 0) }}" placeholder="Enter Sort No.">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <div class="custom-select-wrapper">
                        <select name="is_active" class="form-control custom-select-box">
                            <option value="">Select Status</option>
                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Product Images<span class="text-danger">*</span> (Multiple)</label>
                    <input type="file" name="images[]" class="form-control" multiple>
                    @if ($errors->has('images'))
                        <span class="text-danger">{{ $errors->first('images') }}</span>
                    @endif
                    @if ($errors->has('images.*'))
                        <span class="text-danger">{{ $errors->first('images.*') }}</span>
                    @endif
                </div>

                <div class="col-md-12 mb-3">
                    <label>Bottom Images</label>
                    <input type="file" name="bottom_images[]" class="form-control" multiple>
                </div>
                
                <!-- SEO -->
                <div class="col-md-12 mb-3">
                    <h4>SEO Section</h4>
                </div>
                <div class="col-md-12 mb-3">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="Meta Title">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description" class="form-control" placeholder="Meta Description">{{ old('meta_description') }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Seo Image</label>
                    <input type="file" name="seo_image" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Meta Keywords</label>
                    <input type="text" name="meta_tags" class="form-control" value="{{ old('meta_tags') }}" placeholder="Meta Keywords">
                </div>

            </div>

            <!-- Variant Modal -->
            <div class="modal fade" id="variantModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Product Variants</h5>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="variant_option[]" class="form-control" placeholder="e.g., Red"></td>
                                    <td><input type="number" name="variant_price[]" class="form-control" placeholder="Price"></td>
                                    <td>
                                        <input type="file" name="variant_images[0][]" class="form-control variant-images" multiple>
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveVariant">Save Variant</button>
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
    let specIndex = 1;
        $('#addSpecRow').click(function() {
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

        $(document).on('click', '.removeSpecRow', function() {
            $(this).closest('tr').remove();
        });
    });

    $(document).ready(function() {
        $('#category_id').on('change', function () {
            var category_id = $(this).val();

            $.ajax({
                url: "{{ route('get.subcategories') }}",
                type: "POST",
                data: {
                    category_id: category_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    $('#subcategory_id').html('<option value="">-- Select --</option>');
                    $.each(res, function (key, value) {
                        $('#subcategory_id').append('<option value="'+value.id+'">'+value.category_name+'</option>');
                    });
                }
            });
        });

        // Show/Hide Price Section
        function checkProductType(type) {
            if (type === 'variant') {
                $('#price_section').hide();
                $('#stock_section').hide();
                $('#variant_button_section').show();
                $('#attribute_section').show();
            } else {
                $('#price_section').show();
                $('#stock_section').show();
                $('#variant_button_section').hide();
                $('#attribute_section').hide();
            }
        }

        $('#product_type').on('change', function () {
            checkProductType($(this).val());
        });

        $(document).ready(function () {
            checkProductType($('#product_type').val());
        });

        $('#saveVariant').on('click', function () {
            $('#variantModal').modal('hide');
        });
        
        // Add Variant Row
        $(document).on('click', '#addRow', function () {
            let index = $('#variant_options_table tbody tr').length; 
            let row = `
            <tr>
                <td><input type="text" name="variant_option[]" class="form-control" placeholder="e.g., Red"></td>
                <td><input type="number" name="variant_price[]" class="form-control" placeholder="Price"></td>
                <td>
                    <input type="file" name="variant_images[${index}][]" class="form-control variant-images" multiple>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
            </tr>`;
            $('#variant_options_table tbody').append(row);
        });

        // Remove Variant Row
        $(document).on('click', '.removeRow', function () {
            $(this).closest('tr').remove();
            $('#variant_options_table tbody tr').each(function(i){
                $(this).find('input[type="file"]').attr('name', `variant_images[${i}][]`);
            });
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
                    $(this).find("input[name^='weight_value']").attr("name", `weight_value[${groupIndex}][]`);
                    $(this).find("select[name^='weight_type']").attr("name", `weight_type[${groupIndex}][]`);
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
                        <span class="tag">${value}<i class="fa fa-times remove-tag"></i></span>
                    `);

                    $(this).val('');
                    updateTagCSV(box);
                }
            }
        });

        $(document).on('click', '.remove-tag', function () {
            let box = $(this).closest('.tag-box');
            $(this).parent('.tag').remove();
            updateTagCSV(box);
        });

        function updateTagCSV(box) {
            let name = box.data('name');
            let values = [];
            box.find('.tag').each(function () {
                let text = $(this).clone().children().remove().end().text().trim();
                values.push(text);
            });

            box.find('input[type="hidden"]').remove();
            box.append(`<input type="hidden" name="${name}" value="${values.join(',')}" required>`);
            if (values.length === 0) {
                box.addClass('is-invalid');
            } else {
                box.removeClass('is-invalid');
            }
        }
    });
</script>

@endsection
