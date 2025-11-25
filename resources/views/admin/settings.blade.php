@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="title">
            <h1 class="text-dark">Portal</h1>    	
        </div>
        
        <div class="content">        
            <h2>Settings</h2>
            @if( session()->has('status'))
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> {{ session()->get('status') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="m-0 pb-2"><i class="fa fa-exclamation-triangle"></i> {{ $error }}</p>
                @endforeach
                </div>
            @endif
            <form method="post" action="{{ url('/admin/settings/') }}">
                {{ csrf_field() }}
                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#t1" data-toggle="tab">Store</a></li>
                    <li class="nav-item"><a class="nav-link" href="#t2" data-toggle="tab">SEO</a></li>
                    <li class="nav-item"><a class="nav-link" href="#t3" data-toggle="tab">Notice</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show py-4" id="t1">
                        <div class="form-group">
                            <label class="py-2 d-block">Portal Name</label>
                            <div class="d-block">
                                <input type="text" name="portal_name" class="form-control" placeholder="Store name" >
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="py-2 d-block">Portal Owner</label>
                            <div class="d-block">
                                <input type="text" name="owner" class="form-control" placeholder="Store owner" >
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="py-2 d-block">Portal Address</label>
                            <div class="d-blockposition-relative">
                                <textarea name="address" class="form-control" data-height="150" placeholder="Address" rows="4"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="py-2 d-block">Customer Support</label>
                            <div class="d-blockposition-relative">
                                <textarea name="customer_support" class="form-control" data-height="200"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="py-2 d-block">Email</label>
                            <div class="d-block">
                                <input type="text" name="email" class="form-control" placeholder="Email" value="" >
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="py-2 d-block">Phone</label>
                            <div class="d-block">
                                <input type="text" name="phone" class="form-control" placeholder="Phone" value="" >
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="tab-pane py-4" id="t2">
                        <div class="form-group">
                            <label class="py-2 d-block">Meta Title</label>
                            <div class="d-block">
                                <input type="text" name="title" class="form-control" placeholder="Meta Title" value="" >
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="py-2 d-block">Meta Description</label>
                            <div class="d-block position-relative">
                                <textarea name="description" class="form-control" placeholder="Meta Description" rows="5"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="py-2 d-block">Keywords</label>
                            <div class="d-block">
                                <textarea name="keywords" class="form-control" placeholder="Keywords" rows="5"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="tab-pane py-4" id="t3">
                        <div class="form-group">
                            <label class="py-2 d-block">Notice for website</label>
                            <div class="d-block">
                                <textarea name="notice" class="form-control" placeholder="Notice" data-toggle="summernote" data-height="300"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                
            </form>
            
        </div>
    </div>
@endsection

@section('scripts')
<script>
    CKEDITOR.replace('address', {
        height: 200,
        removeButtons: 'PasteFromWord'
    });

    CKEDITOR.replace('customer_support', {
        height: 200,
        removeButtons: 'PasteFromWord'
    });

    CKEDITOR.replace('notice', {
        height: 200,
        removeButtons: 'PasteFromWord'
    });
</script>
@endsection
