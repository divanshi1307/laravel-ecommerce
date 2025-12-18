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
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AgeGroupController;
use App\Http\Controllers\BabyWeightController;
use App\Http\Controllers\GstController;
use App\Http\Controllers\AdultWaistController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\CouponController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/fix-cache', function () {
    \Artisan::call('optimize:clear');
    return 'cache cleared';
});

Route::get('/create-storage-link', function () {
    // Only allow this in local or staging environment
    if (app()->environment(['local', 'staging'])) {
        try {
            Artisan::call('storage:link');
            return '✅ Storage link created successfully!';
        } catch (\Exception $e) {
            return '❌ Error: ' . $e->getMessage();
        }
    }
    return abort(403);
});


// -------------------------------------------------
// ADMIN ROUTES
// -------------------------------------------------
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
    Route::get('orders', [AdminController::class, 'orders'])->middleware([AdminAuthenticate::class]);
    Route::get('vieworder/{id}', [AdminController::class, 'vieworder'])->middleware([AdminAuthenticate::class]);
    Route::post('vieworder/{id}', [AdminController::class, 'vieworder'])->middleware([AdminAuthenticate::class]);
    Route::post('updateOrderStatus', [AdminController::class, 'updateOrderStatus'])->middleware([AdminAuthenticate::class]);
    Route::post('orderupdate', [AdminController::class, 'orderupdate'])->middleware([AdminAuthenticate::class]);

    // Add or Edit CMS (GET + POST)
    Route::match(['get', 'post'], 'addcms/{id?}', [AdminController::class, 'addcms'])->middleware([AdminAuthenticate::class])->name('cms.add');
    Route::get('cms', [AdminController::class, 'cms'])->middleware([AdminAuthenticate::class])->name('cms.list');
    Route::post('cms/delete', [AdminController::class, 'delete'])->middleware([AdminAuthenticate::class])->name('cms.delete');

   // Add or Edit coupon (GET + POST)
    Route::match(['get', 'post'], 'addcoupon/{id?}', [CouponController::class, 'addcoupon'])->middleware([AdminAuthenticate::class])->name('coupon.add');
    Route::get('coupons', [CouponController::class, 'coupons'])->middleware([AdminAuthenticate::class])->name('coupon.list');
    Route::post('coupons/delete', [CouponController::class, 'delete'])->middleware([AdminAuthenticate::class])->name('coupon.delete');


    Route::match(['get', 'post'], 'add_product_specific_coupon/{id?}', [CouponController::class, 'add_product_specific_coupon'])->middleware([AdminAuthenticate::class])->name('coupon.add.product.specific.coupon');

    Route::get('product_specific_coupons', [CouponController::class, 'product_specific_coupons'])->middleware([AdminAuthenticate::class])->name('product.specific.coupon.list');
    Route::post('product_specific_coupons/delete', [CouponController::class, 'ProductSpecificDelete'])->middleware([AdminAuthenticate::class])->name('product.specific.coupon.delete');

    // GROUPS
    Route::resource('age-groups', AgeGroupController::class);
    Route::resource('baby-weight', BabyWeightController::class);
    Route::resource('gst-module', GstController::class);
    Route::resource('adult-waist', AdultWaistController::class);

});

Route::post('/category/update-status', [CategoryController::class, 'updateStatus'])->name('category.updateStatus');
Route::post('/get-subcategories', [ProductController::class, 'getSubCategories'])->name('get.subcategories');

Route::post('/brands/update-status', [BrandController::class, 'updateStatus'])->name('brands.updateStatus');

Route::delete('/products/remove-image/{id}', [ProductController::class, 'removeImage']);
Route::delete('/products/remove-bottom-image/{id}', [ProductController::class, 'removeBottomImage']);
Route::delete('/variant-image-delete/{id}', [ProductController::class, 'deleteVariantImage'])->name('variant.image.delete');
Route::post('/products/update-status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
Route::post('/locations/update-status', [LocationController::class, 'updateStatus'])->name('locations.updateStatus');
Route::post('/variant-combination/remove-image', [ProductController::class, 'removeVariantImage'])->name('variant-combination.removeImage');

// -------------------------------------------------
// USER'S ROUTE
// -------------------------------------------------

// REGISTER USER
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/guest-login', [AuthController::class, 'guestLogin'])->name('guest.login');


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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search-products', [HomeController::class, 'search'])->name('products.search');
// Show subcategory page
Route::get('/subcategory/{slug}', [HomeController::class, 'subcategoryProducts'])->name('subcategory.products');
//// Show Products Related Brand
Route::get('/brand/{slug}', [HomeController::class, 'brandProducts'])->name('brand.products');

// PRODUCT DETAILS PAGE
Route::get('/product/{slug}', [ProductController::class, 'productDetail'])->name('product.show');
Route::get('/get-attribute-image/{id}', [ProductController::class, 'getAttributeImage']);

// WISHLIST PAGE
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/render', [WishlistController::class, 'render'])->name('wishlist.render');
    Route::post('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

});

//////// CART PAGE

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/order-success/{id}', [CheckoutController::class, 'orderSuccess'])->name('order.success');
});

//////// PRODUCT REVIEW
Route::post('/product/{product}/review', [ProductReviewController::class, 'store'])->name('product.review.store');

//////// MY ACCOUNT PAGE
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile/update', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/view/{id}', [OrderController::class, 'vieworder'])->name('orders.view');
    Route::get('/account/reviews', [AccountController::class, 'reviews'])->name('account.reviews');
});














