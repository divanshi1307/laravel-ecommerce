@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="title">
            <h1 class="text-dark float-left">CMS</h1>
            <a class="btn btn-primary btn-sm float-right" href="{{ url('/admin/addcms') }}" data-toggle="tooltip" data-placement="left" title="Add Page">
                <i class="fa fa-plus-circle text-light"></i>
            </a>
            <div class="clearfix"></div>
        </div>
        
        <div class="content">        
            <h2>Pages</h2>
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
            
            @if(count($results)>0)
                <table class="table table-bordered table-md">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th class="w-75">Title</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    @foreach($results as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->title }}</td>
                            <td class="text-right">
                                <a href="{{ route('cms.add', $data->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <form method="post" action="{{ route('cms.delete') }}" style="display:inline">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <input type="hidden" name="table" value="cms">                            
                                    <input type="hidden" name="action" value="cms">
                                    <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach                	            
                </table>
                <div class="co-sm-12">
                    <div class="float-left">{{ $results->links() }}</div>
                    <div class="text-left float-left">showing {{ $results->count() }} of {{ $results->total() }} Records</div>
                    <div class="clearfix"></div>
                </div>
            @else
                <div class="alert alert-info">
                No results !
                </div>
            @endif
            
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('.delete').on('click',function(){
                if( !confirm("Are you sure want to delete ?") ){				
                    return false;
                }
            });
        });
    </script>
@endsection
