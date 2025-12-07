<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Diaper India')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('public/img/e-logo.jpg') }}">
    
	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/ckeditor/ckeditor4@4.21.0/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Date-Time Picker CSS -->
    <link rel="stylesheet" href="{{ asset('date-time-picker/bootstrap-datetimepicker.min.css') }}" type="text/css">
    <!-- Date-Time Picker JS -->
    <script src="{{ asset('date-time-picker/bootstrap-datetimepicker.min.js') }}"></script>
	

    @stack('styles')
</head>

<body>
    {{-- Main Content --}}
        @include('layouts.header')
        @yield('content')
        @include('layouts.footer')
        
        {{-- <div id="app">
		<div class="d-flex">
            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
            </div>
        </div>
        </div> --}}

    {{-- <div class="loading"><h3><img src="{{ asset('public/img/loading.gif') }}" class="mr-2 icon"></h3></div> --}}
    
    {{-- Page-specific scripts --}}
    @yield('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 1500);
        });
    </script>
</body>
</html>
