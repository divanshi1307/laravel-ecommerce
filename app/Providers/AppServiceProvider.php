<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\CartComposer;
use App\Models\Wishlist;
use App\Models\Category;
use App\Models\CartItem;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $wishlist = auth()->check()
                ? Wishlist::where('user_id', auth()->id())->with('product')->get()
                : collect();

            $view->with('wishlist', $wishlist);

            $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('id', 'ASC')
            ->get();
            $view->with('categories', $categories);

            // Cart Count Fix
            if (auth()->check()) {
                $sessionCartCount = session('cart_count');
                if (!is_null($sessionCartCount)) {
                    $view->with('cartItemsCount', $sessionCartCount);

                } else {
                    $dbCount = CartItem::where('user_id', auth()->id())->count();
                    session()->put('cart_count', $dbCount);
                    $view->with('cartItemsCount', $dbCount);
                }
            }

        });

        View::composer('*', CartComposer::class);

        Paginator::useBootstrap();
    }
}
