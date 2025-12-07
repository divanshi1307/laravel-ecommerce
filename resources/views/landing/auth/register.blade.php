@extends('landing.layout')

@section('content')

<div class="page-wraper">
    <div class="page-content bg-light">
		<section class="px-3">
			<div class="row align-center-center">
				<div class="col-xxl-6 col-xl-6 col-lg-6 start-side-content">
					<div class="dz-bnr-inr-entry">
						<h1>Registration</h1>
						<nav aria-label="breadcrumb text-align-start" class="breadcrumb-row">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
							</ul>
						</nav>
					</div>

					<div class="registration-media">
						<img src="{{ asset('landing/images/registration/pic3.png')}}" alt="register">
					</div>
				</div>

				<div class="col-xxl-6 col-xl-6 col-lg-6 end-side-content">
					<div class="login-area">
						<h2 class="text-secondary text-center">Register Now</h2>
						<p class="text-center m-b30">Welcome, please register your account</p>

						<form action="{{ route('register.store') }}" method="POST" novalidate>
							@csrf

							{{-- Username --}}
							<div class="m-b25">
								<label class="label-title">Username<span class="text-danger">*</span></label>
								<input name="name" class="form-control @error('name') is-invalid @enderror"
									placeholder="Username" type="text" value="{{ old('name') }}">
								@error('name')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>

							{{-- Email --}}
							<div class="m-b25">
								<label class="label-title">Email Address<span class="text-danger">*</span></label>
								<input name="email" class="form-control @error('email') is-invalid @enderror"
									placeholder="Email Address" type="email" value="{{ old('email') }}">
								@error('email')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>

							{{-- Country Code + Phone --}}
							<div class="m-b40">
								<label class="label-title">Phone Number <span class="text-danger">*</span></label>

								<!-- Hidden field for country code -->
								<input type="hidden" name="country_code" id="country_code">

								<input type="tel" id="phone_number" name="phone_number"
									class="form-control @error('phone_number') is-invalid @enderror"
									placeholder="Enter phone number" autocomplete="off">

								@error('phone_number')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>

							{{-- Password --}}
							<div class="m-b40">
								<label class="label-title">Password<span class="text-danger">*</span></label>
								<div class="secure-input">
									<input type="password" name="password" 
										class="form-control dz-password @error('password') is-invalid @enderror" 
										placeholder="Password">
									<div class="show-pass">
										<i class="eye-open fa-regular fa-eye"></i>
									</div>
								</div>
								@error('password')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>

							{{-- Submit --}}
							<div class="text-center">
								<button type="submit" class="btn btn-secondary btnhover text-uppercase me-2">Register</button>
								<a href="{{ route('login.form') }}" 
									class="btn btn-outline-secondary btnhover text-uppercase">Sign In</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

@endsection

@section('script')
<script>
	$(document).ready(function() {
		var input = document.querySelector("#phone_number");

		var iti = window.intlTelInput(input, {
			initialCountry: "in", 
			separateDialCode: true,
			preferredCountries: ["in", "us", "gb", "ae"],
			utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
		});

		var countryData = iti.getSelectedCountryData();
		$("#country_code").val("+" + countryData.dialCode);

		input.addEventListener("countrychange", function() {
			var data = iti.getSelectedCountryData();
			$("#country_code").val("+" + data.dialCode);
		});
	});

</script>
@endsection

