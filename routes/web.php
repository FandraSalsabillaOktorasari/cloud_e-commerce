<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Public Routes ──────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart (accessible to guests and authenticated users)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

// Compare (session-based, no auth needed)
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/{productId}/toggle', [CompareController::class, 'toggle'])->name('compare.toggle');
Route::get('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');

// PC Builder
Route::get('/pc-builder', [\App\Http\Controllers\PcBuilderController::class, 'index'])->name('pc-builder.index');
Route::get('/pc-builder/products/{slot}', [\App\Http\Controllers\PcBuilderController::class, 'products'])->name('pc-builder.products');
Route::post('/pc-builder/add/{slot}', [\App\Http\Controllers\PcBuilderController::class, 'addPart'])->name('pc-builder.add');
Route::delete('/pc-builder/remove/{slot}', [\App\Http\Controllers\PcBuilderController::class, 'removePart'])->name('pc-builder.remove');
Route::get('/pc-builder/check', [\App\Http\Controllers\PcBuilderController::class, 'check'])->name('pc-builder.check');
Route::post('/pc-builder/add-all-to-cart', [\App\Http\Controllers\PcBuilderController::class, 'addAllToCart'])->name('pc-builder.add-all-to-cart');
Route::get('/pc-builder/clear', [\App\Http\Controllers\PcBuilderController::class, 'clear'])->name('pc-builder.clear');

// Guest Order Tracking
Route::get('/track-order', [OrderController::class, 'trackForm'])->name('orders.track');
Route::post('/track-order', [OrderController::class, 'track'])->name('orders.track.submit');

// ── Guest-Only Routes ──────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// ── Authenticated Routes ───────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{productId}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Reviews
    Route::post('/products/{slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Checkout (also accessible to guests — see below)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{orderNumber}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ── Admin Routes ───────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    Route::resource('products', AdminProductController::class);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});
