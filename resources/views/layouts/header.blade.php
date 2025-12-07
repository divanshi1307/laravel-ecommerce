 
@php
$class="";
$url=explode('/',url()->current());
if( in_array('login',$url) ){
	$class="body-login";
}
$current_route = explode('@',\Route::currentRouteAction());
$current_route=$current_route[1];
if( !in_array($current_route,['login','dashboard']) ){
    if(session()->has('loginid') and session()->get('loginid')==2):
    echo '<script>window.location = "/admin/dashboard?user=invalid"; </script>';
    endif;
}
@endphp

<body class="{{ $class }}" >

    @if(Auth::guard('admin')->check())
        <header>
            <h2 class="float-left"><?php echo e(strtoupper(Config::get('app.name'))); ?> | Administration</h2>    
            <ul class="nav float-right">
                <li><a class="nav-link" href="javascript:void(0)"> Last Login: {{ ucwords(Auth::guard('admin')->user()->last_login) }}</a>
                <li><a class="nav-link" href="<?php echo e(url('/admin')); ?>"><i class="fa fa-home"></i> Home</a>
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" arria-labelled-by="#links">
                        <i class="fa fa-user-circle mr-1"></i> {{ ucwords(Auth::guard('admin')->user()->username) }}

                    </a>
                    <div class="dropdown-menu dropdown-menu-right py-2">
                        <a href="<?php echo e(url('/admin/settings')); ?>" class="dropdown-item py-2"><i class="fa fa-gears mr-2"></i> Settings</a>
                        <a href="<?php echo e(url('/admin/changepassword')); ?>" class="dropdown-item py-2"><i class="fa fa-key mr-2"></i> Change Password</a>
                    </div>
                <li><a class="nav-link" href="<?php echo e(url('/admin/logout')); ?>"><i class="fa fa-sign-out"></i> Logout</a>
            </ul>
            <div class="clearfix"></div>
        </header>

        <div class="navigation">
            <div data-simplebar="init" style="height:100%">
                <div class="simplebar-wrapper" style="margin: 0px;">
                    <div class="simplebar-height-auto-observer-wrapper">
                        <div class="simplebar-height-auto-observer"></div>
                    </div>
                    <div class="simplebar-mask">
                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                            <div class="simplebar-content-wrapper">
                                <div class="simplebar-content" style="padding: 0px;">
                                    <div class="navbar-expand-lg">
                                        <button class="navbar-toggler pl-4" data-toggle="collapse" data-target="#navmenu">
                                            <i class="fa fa-align-left"></i> NAVIGATION
                                        </button>

                                        <ul class="collapse navbar-collapse flex-column" id="navmenu">
                                            <li class="{{ Request::is('admin/dashboard') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                                            </li>
                                            <li class="{{ Request::is('admin/categories*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/categories') }}">
                                                    <i class="fa fa-tags"></i> Categories</a>
                                            </li>
                                            <li class="{{ Request::is('admin/brands*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/brands') }}">
                                                    <i class="fa fa-tasks"></i> Brands</a>
                                            </li>
                                            <li class="{{ Request::is('admin/products*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/products') }}">
                                                    <i class="fa fa-gavel"></i> Products</a>
                                            </li>
                                            <li class="{{ Request::is('admin/sliders*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/sliders') }}">
                                                    <i class="fa fa-image"></i> Sliders</a>
                                            </li>
                                            <li class="{{ Request::is('admin/locations*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/locations') }}">
                                                    <i class="fa fa-link"></i> Locations</a>
                                            </li>

                                            <li class="{{ Request::is('admin/orders*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/orders') }}">
                                                    <i class="fa fa-shopping-cart"></i> Orders</a>
                                            </li>
                                            
                                            <li class="{{ Request::is('admin/age-groups*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/age-groups') }}">
                                                    <i class="fa fa-tags"></i> Age Group</a>
                                            </li>

                                            <li class="{{ Request::is('admin/baby-weight*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/baby-weight') }}">
                                                    <i class="fa fa-tags"></i> Baby Weight</a>
                                            </li>

                                            <li class="{{ Request::is('admin/gst-module*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/gst-module') }}">
                                                    <i class="fa fa-tags"></i>GST Module </a>
                                            </li>
                                            <li class="{{ Request::is('admin/adult-waist*') ? 'active-nav' : '' }}">
                                                <a href="{{ url('admin/adult-waist') }}">
                                                    <i class="fa fa-tags"></i>Adult waist size </a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simplebar-placeholder" style="width: auto; height: 751px;"></div>
            </div>
        </div>
        
        <div class="main">
    @endif
    