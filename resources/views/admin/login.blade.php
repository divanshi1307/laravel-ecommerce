@extends('layouts.app')
@section('content')
<div class="login card">
	<div class="card-header text-center">
    	<a href="{{ url('/admin') }}" class="px-5 text-uppercase"><i class="fa fa-lock mr-2"></i> {{Config::get('app.name')}} Adminstrator Login</a>
    </div>
    <div class="card-body pt-4 pb-5 px-5">
        @if( session()->has('status'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> {{ session()->get('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
              	<p class="m-0 p-0"><i class="fa fa-exclamation-triangle"></i> {{ $error }}</p>
              @endforeach
            </div>
        @endif
        <form method="post" action="{{ url('/admin/login') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label>Username</label>
                <div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-user"></i></span>
					</div>
                    <input type="text" name="username" class="form-control" required placeholder="Username">
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-key"></i></span>
					</div>
                    <input type="password" name="password" class="form-control" required placeholder="Password">
                </div>
            </div>
            <div class="text-center pt-2">
				<button type="submit" class="btn btn-sm btn-block btn-warning mx-auto mt-3 py-2 px-3">Login</button>
			</div>
        </form>
    </div>
</div>
@endsection