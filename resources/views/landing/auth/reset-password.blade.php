@extends('landing.layout')

@section('content')

    <div class="page-wraper">
        <div class="page-content bg-light">
            <section class="px-3">
                <div class="row">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 start-side-content">
                        <div class="dz-bnr-inr-entry">
                            <h1>Reset Password</h1>
                            <nav aria-label="breadcrumb text-align-start" class="breadcrumb-row">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html"> Home</a></li>
                                </ul>
                            </nav>	
                        </div>
                        <div class="registration-media">
                            <img src="images/registration/pic3.png" alt="/">
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6 end-side-content justify-content-center">
                        <div class="login-area">
                            <h2 class="text-secondary text-center">Reset Password</h2>
                            <p class="text-center m-b25">Almost done, enter your new password and you're all set to go</p>

                           <form action="{{ route('password.reset') }}" method="POST" novalidate>
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <!-- Old Password -->
                                <div class="m-b30">
                                    <label class="label-title">Old Password <span class="text-danger">*</span></label>

                                    <div class="secure-input position-relative">
                                        <input type="password" name="old_password"
                                            class="form-control pe-5 @error('old_password') is-invalid @enderror"
                                            placeholder="Enter Old Password" required>

                                        <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;">
                                            <i class="fa-regular fa-eye"></i>
                                        </span>
                                    </div>

                                    @error('old_password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="m-b30">
                                    <label class="label-title">New Password <span class="text-danger">*</span></label>

                                    <div class="secure-input position-relative">
                                        <input type="password" name="password"
                                            class="form-control pe-5 @error('password') is-invalid @enderror"
                                            placeholder="Enter New Password" required>

                                        <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;">
                                            <i class="fa-regular fa-eye"></i>
                                        </span>
                                    </div>

                                    @error('password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="m-b30">
                                    <label class="label-title">Confirm Password <span class="text-danger">*</span></label>

                                    <div class="secure-input position-relative">
                                        <input type="password" name="password_confirmation"
                                            class="form-control pe-5"
                                            placeholder="Confirm New Password" required>

                                        <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;">
                                            <i class="fa-regular fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-secondary btnhover">Reset Password</button>
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
        $(document).on('click', '.toggle-password', function() {
            let input = $(this).siblings('input');
            if (input.attr('type') === "password") {
                input.attr('type', 'text');
                $(this).html('<i class="fa-regular fa-eye-slash"></i>');
            } else {
                input.attr('type', 'password');
                $(this).html('<i class="fa-regular fa-eye"></i>');
            }
        });

    });
</script>

@endsection