<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

// ==== Controller Publik ====
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// ==== Controller Admin ====
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// =============================
// Publik - User
// =============================
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/kemeja', [ProductController::class, 'kemeja'])->name('products.kemeja');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');
Route::redirect('/products', '/produk');

Route::get('/production', [ProductionController::class, 'index'])->name('production');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/galeri', [GalleryController::class, 'index']); // Alias lokal
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Testimoni (Publik)
Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimonials.store');


// Custom Order (User, harus login)
Route::middleware('auth')->group(function () {
    Route::get('/custom-order', [CustomOrderController::class, 'create'])->name('custom-order.form');
    Route::post('/custom-order', [CustomOrderController::class, 'store'])->name('custom-order.store');

    // Chat per custom order (user)
    Route::get('/custom-order/{order}/chat', [CustomOrderController::class, 'chat'])->name('custom-order.chat');
    Route::post('/custom-order/{order}/chat', [CustomOrderController::class, 'sendMessage'])->name('custom-order.chat.send');
});

// =============================
// Autentikasi Wajib
// =============================
Route::middleware('auth')->group(function () {
    // Order User
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::post('/cart/checkout', [CheckoutController::class, 'store'])->name('cart.checkout');
    Route::post('/cart/checkout/{item}', [CartController::class, 'checkoutItem'])->name('cart.checkout.item');
});

// =============================
// Redirect Dashboard
// =============================
Route::get('/dashboard', function () {
    $user = Auth::user();
    return $user && $user->is_admin
        ? redirect()->route('admin.dashboard')
        : redirect()->route('home.index');
})->middleware('auth')->name('dashboard');

// =============================
// Admin Routes
// =============================
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Produk & Varian
    Route::resource('products', AdminProductController::class)->names('products');
    Route::patch('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    // Varian Produk
    Route::get('products/{product}/variants', [ProductVariantController::class, 'index'])->name('variants.index');
    Route::get('products/{product}/variants/create', [ProductVariantController::class, 'create'])->name('variants.create');
    Route::post('products/{product}/variants', [ProductVariantController::class, 'store'])->name('variants.store');
    Route::get('products/{product}/variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
    Route::put('products/{product}/variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
    Route::patch('products/{product}/variants/{variant}/toggle-featured', [ProductVariantController::class, 'toggleFeatured'])->name('variants.toggleFeatured');

    // Galeri
    Route::resource('galleries', AdminGalleryController::class)->names('galleries');

    // Testimoni
    Route::resource('testimonials', AdminTestimonialController::class)
        ->except(['create', 'store', 'show'])
        ->names('testimonials');

    // Custom Order
    Route::get('custom-orders', [CustomOrderController::class, 'adminIndex'])->name('custom-orders.index');
    Route::get('custom-orders/{order}/chat', [CustomOrderController::class, 'adminChat'])->name('custom-orders.chat');
    Route::post('custom-orders/{order}/chat', [CustomOrderController::class, 'adminSendMessage'])->name('custom-orders.chat.send');
    Route::delete('custom-orders/{order}', [CustomOrderController::class, 'destroy'])->name('custom-orders.destroy');

    // Order User
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
});

// =============================
// API: Tandai Notifikasi Terbaca
// =============================
Route::post('/notif/mark-as-read', function (Request $request) {
    if (Auth::check()) {
        Order::where('user_id', Auth::id())
            ->where('user_notified', false)
            ->update(['user_notified' => true]);
    }
    return response()->json(['success' => true]);
})->middleware('auth');

// =============================
// Auth Routes (Laravel Breeze/Fortify/etc.)
// =============================
require __DIR__ . '/auth.php';
