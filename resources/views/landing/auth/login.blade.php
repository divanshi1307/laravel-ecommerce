@extends('landing.layout')

@section('content')

<div class="page-wraper">
    <div class="page-content bg-light">
        <section class="px-3">
            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 start-side-content">
                    <div class="dz-bnr-inr-entry">
                        <h1>Login</h1>
                        <nav aria-label="breadcrumb text-align-start" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"> Home</a></li>
                                <li class="breadcrumb-item">Login</li>
                            </ul>
                        </nav>	
                    </div>

                    <div class="registration-media">
                        <img src="{{ asset('landing/images/registration/pic3.png')}}" alt="register">
                    </div>
                </div>

                <div class="col-xxl-6 col-xl-6 col-lg-6 end-side-content justify-content-center">
                    <div class="login-area">
                        <h2 class="text-secondary text-center">Login</h2>
                        <p class="text-center m-b25">Welcome, please login to your account</p>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            {{-- Email --}}
                            <div class="m-b30">
                                <label class="label-title">Email Address<span class="text-danger">*</span></label>
                                <input name="email" value="{{ old('email') }}" 
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email Address" type="email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="m-b15">
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

                            <div class="form-row d-flex justify-content-between m-b30">
								<div class="form-group">
								   <div class="custom-control custom-checkbox">
										<input type="checkbox" class="form-check-input" id="basic_checkbox_1">
										<label class="form-check-label" for="basic_checkbox_1">Remember Me</label>
									</div>
								</div>
								<div class="form-group">
									<a class="text-primary" href="{{"/forgot-password"}}">Forgot Password</a>
								</div>
							</div>

                            {{-- Submit --}}
                            <div class="text-center">
                                <button type="submit" class="btn btn-secondary btnhover text-uppercase me-2 sign-btn">
                                    Sign In
                                </button>
                                <a href="{{ route('register.form') }}" class="btn btn-outline-secondary btnhover text-uppercase">
                                    Register
                                </a>
                            </div>

                        </form>
                    </div> 
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
