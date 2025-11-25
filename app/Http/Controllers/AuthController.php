<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\PasswordReset;

class AuthController extends Controller
{
    // ======================
    // SHOW REGISTER FORM
    // ======================
    public function showRegisterForm()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        return view('landing.auth.register', compact('categories'));
    }


    // ======================
    // REGISTER USER
    // ======================
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
        // $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => [
                'required','string','min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ],
            'phone_number'        => 'required|numeric|digits_between:7,15|unique:users,phone_number',
            'country_code' => 'required|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'phone_number'        => $request->phone_number,
            'country_code' => $request->country_code,
            'is_verified' => false,
        ]);

        $otp = rand(100000, 999999);
        UserOtp::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Email Verification OTP');
        });

        session(['email' => $user->email]);

        return redirect()->route('otp.verify.page')
            ->with('success', 'OTP sent to your email');
    }

    // ======================
    // SHOW LOGIN FORM
    // ======================
    public function showLoginForm()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        return view('landing.auth.login', compact('categories'));
    }


    // ======================
    // LOGIN USER
    // ======================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found']);
        }

        if (!$user->is_verified) {
            return back()->withErrors(['email' => 'Your email is not verified.']);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return redirect()->route('home')
                ->with('success', 'Login Successful!');
        }

        return back()->withErrors(['email' => 'Invalid email or password']);
    }


    // ======================
    // SHOW OTP PAGE
    // ======================
    public function verifyOtpForm()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Session expired! Please login again.');
        }

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        return view('landing.auth.verify_otp', compact('categories', 'email'));
    }


    // ======================
    // SEND OTP (RESEND OTP)
    // ======================
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;
        session(['email' => $email]);

        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => '', 'password' => '', 'is_verified' => false]
        );

        $otp = rand(100000, 999999);

        UserOtp::updateOrCreate(
            ['user_id' => $user->id],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your OTP');
        });

        return redirect()->route('otp.verify.page')
            ->with('success', 'OTP sent successfully!');
    }


    // ======================
    // VERIFY OTP
    // ======================
    public function verifyOtp(Request $request)
    {
        $email = session('email');

        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $otpData = UserOtp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpData) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        $user->update(['is_verified' => true]);
        UserOtp::where('user_id', $user->id)->delete();
        Auth::login($user);
        session()->forget('email');
        return redirect()->route('home')->with('success', 'Email Verified Successfully!');
    }

    // ======================
    // LOGOUT
    // ======================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logged out successfully');
    }

    // ======================
    // FORGOT PASSWORD
    // ======================

    public function showForgotForm()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();
        return view('landing.auth.forgot-password', compact('categories'));
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.'])->withInput();
        }

        $token = Str::random(60);
        PasswordReset::updateOrCreate(['email' => $request->email],['token' => $token]);

        Mail::raw("Your password reset link: " . url('/reset-password?token='.$token), function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset Link');
        });

        return back()->with('success', 'Password reset link sent to your email.');
    }

    // ======================
    // RESET PASSWORD
    // ======================

    public function showResetForm(Request $request)
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();

        return view('landing.auth.reset-password', [
            'categories' => $categories,
            'token'      => $request->token,
        ]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|min:6|confirmed',
            'token'        => 'required',
        ]);

        $resetEntry = PasswordReset::where('token', $request->token)->first();
        if (!$resetEntry) {
            return back()->with('error', 'Invalid or expired token.');
        }

        $user = User::where('email', $resetEntry->email)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Check if old password 
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors([
                'old_password' => 'Old password does not match.',
            ])->withInput();
        }

        // Prevent new password from being same as old password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'New password cannot be the same as old password.',
            ])->withInput();
        }

        // Update user password
        $user->update([
            'old_password' => $user->password,      
            'password'     => Hash::make($request->password),
        ]);

        PasswordReset::where('email', $resetEntry->email)->delete();
        return redirect()->route('login')->with('success', 'Password reset successfully! You can login now.');
    }

}
