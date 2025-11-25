<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search-products', [HomeController::class, 'search'])->name('products.search');
// Show subcategory page
Route::get('/subcategory/{id}', [HomeController::class, 'subcategoryProducts']);

// PRODUCT DETAILS PAGE
Route::get('/product/{id}', [ProductController::class, 'productDetail'])->name('product.show');





Route::get('admin', [AdminController::class, 'main']);
Route::prefix('admin')->group(function(){
	
    Route::get('login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('login', [AdminController::class, 'login']);
    Route::get('logout', [AdminController::class, 'logout']);

    Route::get('dashboard', [AdminController::class, 'dashboard'])
    ->middleware([AdminAuthenticate::class])->name('admin.dashboard');

    Route::get('changepassword', [AdminController::class, 'changepassword'])
    ->middleware([AdminAuthenticate::class])->name('admin.changepassword');
    Route::post('changepassword', [AdminController::class, 'changepassword'])
    ->middleware([AdminAuthenticate::class])->name('admin.changepassword');

    Route::get('settings', [AdminController::class, 'settings'])
    ->middleware([AdminAuthenticate::class])->name('admin.settings');
    Route::post('settings', [AdminController::class, 'settings'])
    ->middleware([AdminAuthenticate::class])->name('admin.settings');
    
    Route::resource('/categories', CategoryController::class);
    Route::resource('/brands', BrandController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/sliders', SliderController::class);
    Route::resource('/locations', LocationController::class);
});

Route::post('/category/update-status', [CategoryController::class, 'updateStatus'])->name('category.updateStatus');
Route::post('/get-subcategories', [ProductController::class, 'getSubCategories'])->name('get.subcategories');

Route::post('/brands/update-status', [BrandController::class, 'updateStatus'])->name('brands.updateStatus');

Route::delete('/products/remove-image/{id}', [ProductController::class, 'removeImage']);
Route::delete('/variant-image-delete/{id}', [ProductController::class, 'deleteVariantImage'])->name('variant.image.delete');
Route::post('/products/update-status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
Route::post('/locations/update-status', [LocationController::class, 'updateStatus'])->name('locations.updateStatus');
Route::post('/variant-combination/remove-image', [ProductController::class, 'removeVariantImage'])
      ->name('variant-combination.removeImage');

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Verify OTP
Route::get('/verify-otp', [AuthController::class, 'verifyOtpForm'])->name('otp.verify.page');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('otp.send');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.forgot.page');
Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.forgot');

// Reset Password
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset.page');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');










