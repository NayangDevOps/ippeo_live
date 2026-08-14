<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\IntegrationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/shop', [StoreController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [StoreController::class, 'product'])->name('product.show');
Route::get('/categories', [StoreController::class, 'categories'])->name('categories');
Route::get('/new-launches', [StoreController::class, 'newLaunches'])->name('new-launches');
Route::get('/page/{slug}', [StoreController::class, 'page'])->name('page.show');
Route::get('/contact', [StoreController::class, 'contact'])->name('contact');
Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StoreController::class, 'placeOrder'])->name('checkout.place');
Route::post('/checkout/razorpay/verify', [StoreController::class, 'verifyRazorpay'])->name('checkout.razorpay.verify');
Route::get('/order-success/{orderNumber}', [StoreController::class, 'orderSuccess'])->name('order.success');
Route::post('/enquiry', [StoreController::class, 'enquiry'])->name('enquiry.submit');
Route::post('/newsletter', [StoreController::class, 'newsletter'])->name('newsletter');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [DashboardController::class, 'loginForm'])->name('login');
    Route::post('login', [DashboardController::class, 'login'])->name('login.post');

    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::post('logout', [DashboardController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class)->except(['show']);
        Route::delete('product-images/{image}', [ProductController::class, 'deleteImage'])->name('product-images.destroy');
        Route::delete('product-videos/{video}', [ProductController::class, 'deleteVideo'])->name('product-videos.destroy');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::resource('pages', CmsPageController::class)->except(['show']);

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');

        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

        Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
        Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('integrations', [IntegrationController::class, 'edit'])->name('integrations.edit');
        Route::post('integrations', [IntegrationController::class, 'update'])->name('integrations.update');
        Route::post('integrations/test-mail', [IntegrationController::class, 'testMail'])->name('integrations.test-mail');
        Route::post('integrations/test-razorpay', [IntegrationController::class, 'testRazorpay'])->name('integrations.test-razorpay');
    });
});
