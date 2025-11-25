@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="title">
            <h1 class="text-dark">Settings</h1>    	
        </div>
        
        <div class="content">        
            <h2>Change Password</h2>
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
            <form method="post" action="{{ url('/admin/changepassword/') }}">
                {{ csrf_field() }}
                <div class="col-sm-2 float-right text-right mb-4">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save pr-1"></i> save</button>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Old Password</label>
                    <div class="float-left col-sm-10">
                        <input type="text" name="opass" class="form-control" placeholder="Old Password" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">New Password</label>
                    <div class="float-left col-sm-10">
                        <input type="text" name="npass" class="form-control" placeholder="New Password" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                
                <div class="form-group">
                    <label class="col-sm-2 float-left text-right">Confirm Password</label>
                    <div class="float-left col-sm-10">
                        <input type="text" name="cpass" class="form-control" placeholder="Confirm Password" >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="my-4">
                
                
            </form>
            
        </div>
    </div>
@endsection
