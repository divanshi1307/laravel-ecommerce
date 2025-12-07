<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="index, follow">
	<meta name="format-detection" content="telephone=no">
	
	<meta name="keywords" content="template, ui kit, clothing, delivery, ecommerce, fashion, order, shopping, store, fashion design, fashion store, responsive design, fashion showcase, modern design, fashion technology, e-shop, ecommerce web, eCommerce Website, minimal shop, online shop, online shopping, pixio, user experience, Design Elements, Trendy, Stylish, User-Friendly, Navigation, Product Display, Branding, Development, Visual Design, UI/UX, Website, Web Design">
	<meta name="description" content="Elevate your online retail presence with Pixio Shop & eCommerce HTML Template. Crafted with precision, this responsive and feature-rich template provides a seamless and visually stunning shopping experience. Explore a world of possibilities with modern design elements, intuitive navigation, and customizable features. Transform your website into a dynamic online storefront with Pixio, where style meets functionality for a captivating and user-friendly eCommerce journey.">
	
	<meta property="og:title" content="Pixio: Shop & eCommerce Bootstrap HTML Template | DexignZone">
	<meta property="og:description" content="Elevate your online retail presence with Pixio Shop & eCommerce HTML Template. Crafted with precision, this responsive and feature-rich template provides a seamless and visually stunning shopping experience. Explore a world of possibilities with modern design elements, intuitive navigation, and customizable features. Transform your website into a dynamic online storefront with Pixio, where style meets functionality for a captivating and user-friendly eCommerce journey.">
	<meta property="og:image" content="https://pixio.dexignzone.com/xhtml/social-image.png">
	
	<!-- TWITTER META -->
	<meta name="twitter:title" content="Pixio: Shop & eCommerce Bootstrap HTML Template | DexignZone">
	<meta name="twitter:description" content="Elevate your online retail presence with Pixio Shop & eCommerce HTML Template. Crafted with precision, this responsive and feature-rich template provides a seamless and visually stunning shopping experience. Explore a world of possibilities with modern design elements, intuitive navigation, and customizable features. Transform your website into a dynamic online storefront with Pixio, where style meets functionality for a captivating and user-friendly eCommerce journey.">
	<meta name="twitter:image" content="https://pixio.dexignzone.com/xhtml/social-image.png">
	<meta name="twitter:card" content="summary_large_image">
	
	<!-- CANONICAL URL -->
	<link rel="canonical" href="https://pixio.dexignzone.com/xhtml/index.html">
	
	<!-- FAVICONS ICON -->
	<link rel="icon" type="image/x-icon" href="images/favicon.png">
	
	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- STYLESHEETS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('landing/css/index.min.css') }}" >
	<link rel="stylesheet" type="text/css" href="{{ asset('landing/css/magnific-popup.min.css') }}">	
	{{-- <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/bootstrap-select.min.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('landing/css/swiper-bundle.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('landing/css/nouislider.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('landing/css/animate.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('landing/css/lightgallery.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/lg-thumbnail.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/lg-zoom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/slick.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"/>
	
	<!-- Custom Stylesheet -->
	<link class="main-css" rel="stylesheet" type="text/css" href="{{asset('landing/css/style.css')}}">
	<link class="skin" type="text/css" rel="stylesheet" href="{{asset('landing/css/skin-1.css')}}">

	<!-- GOOGLE FONTS-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

  @yield('style')

</head>

<body id="bg">
<!-- start header section -->
{{-- @include('landing.partials.header', ['categories' => $categories]) --}}
@include('landing.partials.header')
<!-- end header section -->

<div class="min-h-screen">
    <main>
        {{-- Global Session Alerts --}}
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- start footer section -->
@include('landing.partials.footer', ['categories' => $categories])
<!-- end footer section -->

<!-- JAVASCRIPT FILES ========================================= -->
    <script src="{{ asset('landing/js/jquery.min.js') }}"></script>
    <script src="{{ asset('landing/js/custom.js') }}"></script>
    <script src="{{ asset('landing/js/wow.min.js') }}"></script>
    {{-- <script src="{{ asset('landing/js/bootstrap.bundle.min.js') }}"></script> --}}
    
    {{-- Add to cart  --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Quick View Product  --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>  --}}


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>
    {{-- <script src="{{ asset('landing/js/bootstrap-select.min.js') }}"></script>  --}}
    <script src="{{ asset('landing/js/bootstrap-touchspin.js') }}"></script> 
    <script src="{{ asset('landing/js/swiper-bundle.min.js') }}"></script> 
    <script src="{{ asset('landing/js/magnific-popup.js') }}"></script> 
    <script src="{{ asset('landing/js/imagesloaded.js') }}"></script> 
    <script src="{{ asset('landing/js/masonry-4.2.2.js') }}"></script> 
    <script src="{{ asset('landing/js/isotope.pkgd.min.js') }}"></script> 
    <script src="{{ asset('landing/js/jquery.countdown.js') }}"></script> 
    <script src="{{ asset('landing/js/wNumb.js') }}"></script> 
    <script src="{{ asset('landing/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('landing/js/slick.min.js') }}"></script> 
    <script src="{{ asset('landing/js/lightgallery.min.js') }}"></script>
    <script src="{{ asset('landing/js/lg-thumbnail.min.js') }}"></script>
    <script src="{{ asset('landing/js/lg-zoom.min.js') }}"></script>
    <script src="{{ asset('landing/js/dz.carousel.js') }}"></script>
    {{-- <script src="{{ asset('landing/js/dz.ajax.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>

@yield('script')
<script>
    setTimeout(function() {
        let alertBox = document.querySelector('.alert');
        if (alertBox) {
            alertBox.style.transition = "0.5s";
            alertBox.style.opacity = "0";

            setTimeout(() => alertBox.remove(), 500); 
        }
    }, 2500);
</script>

</body>
</html>
