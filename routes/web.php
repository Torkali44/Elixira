<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController; // Breeze
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AvatarOptionController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/explore', [HomeController::class, 'explore'])->name('explore');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{item}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

Route::get('/track-order', [\App\Http\Controllers\OrderController::class, 'track'])->name('orders.track');

Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');

Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
Route::post('/newsletter/subscribe', [TestimonialController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/special-requests', [\App\Http\Controllers\SpecialRequestController::class, 'store'])->name('special-requests.store');

// Breeze Auth Routes
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders.index');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'showOrder'])->name('profile.orders.show');
    Route::get('/profile/orders/{order}/invoice', [ProfileController::class, 'invoice'])->name('profile.orders.invoice');
    Route::get('/profile/avatar-options', [ProfileController::class, 'avatarOptions'])->name('profile.avatar-options');
    Route::patch('/profile/avatar-options', [ProfileController::class, 'updateAvatarOption'])->name('profile.avatar-options.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard.redirect');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::delete('items/images/{image}', [ItemController::class, 'deleteImage'])->name('items.delete-image');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::patch('users/{user}/suspend', [\App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
    
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

    Route::resource('reviews', ReviewController::class)->only(['index', 'destroy', 'create', 'store']);
    Route::patch('reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::patch('avatar-options/{avatarOption}/toggle', [AvatarOptionController::class, 'toggle'])->name('avatar-options.toggle');
    Route::resource('avatar-options', AvatarOptionController::class)->except(['show']);

    Route::get('special-requests', [\App\Http\Controllers\SpecialRequestController::class, 'index'])->name('special-requests.index');
    Route::patch('special-requests/{specialRequest}/status', [\App\Http\Controllers\SpecialRequestController::class, 'updateStatus'])->name('special-requests.updateStatus');
});

require __DIR__.'/auth.php';
