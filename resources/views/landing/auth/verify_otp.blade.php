@extends('landing.layout')

@section('content')

<div class="page-wraper">
    <div class="page-content bg-light">
		<section class="px-3">
			<div class="row align-center-center">
				<div class="col-xxl-6 col-xl-6 col-lg-6 start-side-content">
					<div class="dz-bnr-inr-entry">
						<h1>OTP Verification</h1>
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

                        <h2 class="text-secondary text-center">OTP Verification</h2>
                        <p class="text-center mb-4">Please verify the OTP sent to your email</p>

                        <form action="{{ route('otp.verify') }}" method="POST" novalidate>
                            @csrf

                            <input type="hidden" name="email" value="{{ session('email') }}">

                            <label class="form-label">Enter OTP</label>
                            <input type="text" name="otp" maxlength="6" minlength="6" required pattern="[0-9]{6}"
                                class="form-control @error('otp') is-invalid @enderror">

                            @error('otp')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror

                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                Verify OTP
                            </button>

                        </form>
                    </div>
                </div>
			</div>
		</section>
	</div>
</div>

@endsection
