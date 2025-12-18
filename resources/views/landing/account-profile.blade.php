@extends('landing.layout')

@section('content')
    <div class="page-wraper">
        <div class="page-content bg-light">
            <!--Banner Start-->
            <div class="dz-bnr-inr bg-secondary overlay-black-light" style="background-image:url(images/background/bg1.jpg);">
                <div class="container">
                    <div class="dz-bnr-inr-entry">
                        <h1>Profile</h1>
                        <nav aria-label="breadcrumb" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{"/"}}"> Home</a></li>
                                <li class="breadcrumb-item">Account Profile</li>
                            </ul>
                        </nav>
                    </div>
                </div>	
            </div>
            <!--Banner End-->
            
            <div class="content-inner-1">
                <div class="container">
                    <div class="row">
                        <aside class="col-xl-3">
                            <div class="toggle-info">
                                <h5 class="title mb-0">Account Navbar</h5>
                                <a class="toggle-btn" href="#accountSidebar">Account Menu</a>
                            </div>
                            <div class="sticky-top account-sidebar-wrapper">
                                <div class="account-sidebar" id="accountSidebar">
                                    <div class="profile-head">
                                        <div class="user-thumb">
                                            <img src="{{ auth()->user()->profile_image ? asset('uploads/profile/' . auth()->user()->profile_image) : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }}" 
                                            alt="User Avatar" class="rounded-circle">
                                            
                                        </div>
                                        <h5 class="title mb-0">{{ auth()->user()->name }}</h5>
                                        <span class="text text-primary">{{ auth()->user()->email }}</span>
                                    </div>
                                    <div class="account-nav">
                                        <div class="nav-title bg-light">DASHBOARD</div>
                                        <ul>
                                            <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                                            <li><a href="{{ route('orders.index') }}">Orders</a></li>
                                        </ul>
                                        <div class="nav-title bg-light">ACCOUNT SETTINGS</div>
                                        <ul class="account-info-list">
                                            <li><a href="{{route('account.profile')}}">Profile</a></li>
                                            <li><a href="{{route('account.reviews')}}">Review</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </aside>
                        <section class="col-xl-9 account-wrapper">
                            <div class="account-card">
                                <div class="profile-edit">
                                    <div class="avatar-upload d-flex align-items-center">
                                        <div class="position-relative">
                                            <div class="avatar-preview thumb">
                                                <div id="imagePreview"
                                                    style="background-image: url({{ $user->profile_image 
                                                        ? asset('uploads/profile/'.$user->profile_image)
                                                        : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }});">
                                                </div>
                                            </div>

                                            <!-- CAMERA BUTTON — outside form -->
                                            <div class="change-btn thumb-edit d-flex align-items-center flex-wrap">
                                                <label for="hiddenProfileInput" class="btn btn-light ms-0">
                                                    <i class="fa-solid fa-camera"></i>
                                                </label>
                                            </div>	
                                        </div>
                                    </div>

                                    <div class="clearfix">
                                        <h2 class="title mb-0">{{ auth()->user()->name }}</h2>
                                        <span class="text text-primary">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>

                                <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data" class="row">
                                    @csrf
                                    <input type="file" id="hiddenProfileInput" name="profile_image" class="d-none" accept=".jpg,.jpeg,.png">
                                    <div class="col-lg-6">
                                        <div class="form-group m-b25">
                                            <label class="label-title">First Name</label>
                                            <input name="first_name" class="form-control" value="{{ explode(' ', $user->name)[0] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-b25">
                                            <label class="label-title">Last Name</label>
                                            <input name="last_name" class="form-control" value="{{ explode(' ', $user->name)[1] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-b25">
                                            <label class="label-title">Email address</label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-b25">
                                            <label class="label-title">Phone</label>

                                            <!-- Country Code -->
                                            <input type="hidden" name="country_code" id="country_code" value="{{ $user->country_code }}">

                                            <!-- Phone Number -->
                                            <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="Enter phone number" value="{{ $user->phone_number }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group m-b25">
                                            <label class="label-title">New password <span class="text-danger">*</span></label>
                                            <input type="password" name="password" required class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group m-b25">
                                            <label class="label-title">Confirm new password <span class="text-danger">*</span></label>
                                            <input type="password" name="password_confirmation" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button class="btn btn-primary mt-3 mt-sm-0" type="submit">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
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

        document.getElementById("hiddenProfileInput").addEventListener("change", function(){
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e){
                    document.getElementById("imagePreview").style.backgroundImage = "url(" + e.target.result + ")";
                };
                reader.readAsDataURL(file);
            }
        });

	});

</script>
@endsection