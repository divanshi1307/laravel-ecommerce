@extends('landing.layout')

@section('content')

    <div class="page-wraper">
        <div class="page-content bg-light">
            <section class="px-3">
                <div class="row">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 start-side-content">
                        <div class="dz-bnr-inr-entry">
                            <h1>Forgot Password</h1>
                            <nav aria-label="breadcrumb text-align-start" class="breadcrumb-row">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html"> Home</a></li>
                                    <li class="breadcrumb-item">Forgot Password</li>
                                </ul>
                            </nav>	
                        </div>
                        <div class="registration-media">
                            <img src="images/registration/pic3.png" alt="/">
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6 end-side-content justify-content-center">
                        <div class="login-area">
                            <h2 class="text-secondary text-center">Forgot Password</h2>
                            <p class="text-center m-b25">Enter your e-mail address below to reset your password.</p>

                            <form action="{{ route('password.forgot') }}" method="POST" novalidate>
                                @csrf
                                <div class="m-b30">
                                    <label class="label-title">Email Address<span class="text-danger">*</span></label>
                                    <input name="email" value="{{ old('email') }}" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Email Address" type="email" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btnhover">Back</a>
                                    <button type="submit" class="btn btn-secondary btnhover">Submit</button>
                                </div>
                            </form>
                        </div> 
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection