@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="title">
            <h1 class="text-dark">CMS</h1>    	
        </div>
        
        <div class="content">        
            <h2>Add Page</h2>
            @if( session()->has('status'))
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> {{ session()->get('status') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="m-0 p-1"><i class="fa fa-exclamation-triangle"></i> {{ $error }}</p>
                @endforeach
                </div>
            @endif
            <form method="post" action="{{ url('/admin/addcms/'.$id) }}">
                {{ csrf_field() }}
                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="text-left">Title</label>
                    <div class="text-left">
                        <input type="text" name="title" class="form-control" placeholder="Title" value="{{ isset($data) ? $data->title : "" }}" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <label class="text-left">Description</label>
                    <div class="text-left">
                        <textarea name="description" id="description" class="form-control" placeholder="Description" rows="5">{{ isset($data) ? $data->description : "" }}</textarea>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <label class="text-left">Meta Title</label>
                    <div class="text-left">
                        <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="{{ isset($data) ? $data->meta_title : "" }}" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <label class="text-left">Meta Keywords</label>
                    <div class="text-left">
                        <input type="text" name="meta_keywords" class="form-control" placeholder="Meta Keywords" value="{{ isset($data) ? $data->meta_keywords : "" }}" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <label class="text-left">Meta Description</label>
                    <div class="text-left">
                        <textarea name="meta_desc" class="form-control" placeholder="Meta Description">{{ isset($data) ? $data->meta_desc : "" }}</textarea>
                    </div>
                    <div class="clearfix"></div>
                </div> 		
            </form>
            
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            CKEDITOR.replace('description', {
                height: 200,
                removeButtons: 'PasteFromWord'
            });
        });
    </script>
@endsection


