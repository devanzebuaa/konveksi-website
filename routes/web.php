<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

// Controller Publik (User)
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
use App\Http\Controllers\CheckoutController; // ✅ Tambahkan ini

// Controller Admin
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// =======================
// ✅ Publik - User
// =======================
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/kemeja', [ProductController::class, 'kemeja'])->name('products.kemeja');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');
Route::redirect('/products', '/produk');

Route::get('/production', [ProductionController::class, 'index'])->name('production');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/galeri', [GalleryController::class, 'index']);
Route::get('/about', [AboutController::class, 'index'])->name('about');

// ✅ Testimoni Publik
Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimonials.store');

// ✅ Order & Cart (Login Required)
Route::middleware('auth')->group(function () {
    // Order
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // ✅ Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');

    // ✅ Checkout Semua
    Route::post('/cart/checkout', [CheckoutController::class, 'store'])->name('cart.checkout');

    // ✅ Checkout Per Item (Fix yang hilang!)
    Route::post('/cart/checkout/{item}', [CartController::class, 'checkoutItem'])->name('cart.checkout.item');
});

// ✅ Custom Order (Tanpa login pun bisa)
Route::get('/custom-order', [CustomOrderController::class, 'create'])->name('custom-order.form');
Route::post('/custom-order', [CustomOrderController::class, 'store'])->name('custom-order.store');

// =======================
// ✅ Dashboard Redirect
// =======================
Route::get('/dashboard', function () {
    $user = Auth::user();
    return $user && $user->is_admin
        ? redirect()->route('admin.dashboard')
        : redirect()->route('home.index');
})->middleware('auth')->name('dashboard');

// =======================
// ✅ Dashboard Admin
// =======================
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::resource('products', AdminProductController::class)->names('admin.products');

    // ✅ Varian Produk
    Route::get('products/{product}/variants', [ProductVariantController::class, 'index'])->name('admin.variants.index');
    Route::get('products/{product}/variants/create', [ProductVariantController::class, 'create'])->name('admin.variants.create');
    Route::post('products/{product}/variants', [ProductVariantController::class, 'store'])->name('admin.variants.store');
    Route::get('products/{product}/variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('admin.variants.edit');
    Route::put('products/{product}/variants/{variant}', [ProductVariantController::class, 'update'])->name('admin.variants.update');
    Route::delete('products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('admin.variants.destroy');

    // ✅ Galeri
    Route::resource('galleries', AdminGalleryController::class)->names('admin.galleries');

    // ✅ Testimoni
    Route::resource('testimonials', AdminTestimonialController::class)
        ->except(['create', 'store', 'show'])
        ->names('admin.testimonials');

    // ✅ Toggle Produk Featured
    Route::patch('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('admin.products.toggle-featured');

    // ✅ Custom Order Admin Panel
    Route::get('custom-orders', [CustomOrderController::class, 'adminIndex'])->name('admin.custom-orders.index');
    Route::delete('custom-orders/{order}', [CustomOrderController::class, 'destroy'])->name('admin.custom-orders.destroy');

    // ✅ User Order Admin Panel
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
});

// =======================
// ✅ API: Tandai notifikasi sudah dibaca
// =======================
Route::post('/notif/mark-as-read', function (Request $request) {
    if (Auth::check()) {
        Order::where('user_id', Auth::id())
            ->where('user_notified', false)
            ->update(['user_notified' => true]);
    }

    return response()->json(['success' => true]);
})->middleware('auth');

// =======================
// ✅ Auth Routes
// =======================
require __DIR__ . '/auth.php';
