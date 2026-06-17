<?php

use App\Http\Controllers\Admin\AvatarOptionController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DxnSponsorCodeController;
use App\Http\Controllers\Admin\DxnTeamRequestController as AdminDxnTeamRequestController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\NewsletterController; // Breeze
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductApprovalController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorRequestController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DxnTeamRequestController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpecialRequestController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\VendorProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::redirect('/join-dxn', '/become-dxn-distributor');
Route::get('/become-dxn-distributor', [DxnTeamRequestController::class, 'create'])->name('dxn-distributor.create');
Route::post('/become-dxn-distributor', [DxnTeamRequestController::class, 'store'])->name('dxn-distributor.store');
Route::post('/become-dxn-distributor/existing-member', [DxnTeamRequestController::class, 'storeExistingMember'])->name('dxn-distributor.existing-member');
Route::get('/join-dxn', fn () => redirect()->route('dxn-distributor.create'))->name('dxn-team.create');
Route::post('/join-dxn', [DxnTeamRequestController::class, 'store'])->name('dxn-team.store');
Route::get('/explore', [HomeController::class, 'explore'])->name('explore');
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');
Route::get('/theme/{theme}', [ThemeController::class, 'switch'])->name('theme.switch');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{item}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

Route::get('/track-order', [OrderController::class, 'track'])->name('orders.track');

Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');

Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
Route::post('/newsletter/subscribe', [TestimonialController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/special-requests', [SpecialRequestController::class, 'store'])->name('special-requests.store');

Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brand:slug}', [BrandController::class, 'show'])->name('brands.show');

Route::view('/vendor/terms', 'vendor.terms')->name('vendor.terms');

// Breeze Auth Routes
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->role === 'vendor') {
        return redirect()->route('vendor.dashboard');
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

    Route::post('/profile/addresses', [ProfileController::class, 'addAddress'])->name('profile.addresses.store');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('profile.addresses.destroy');
    Route::patch('/profile/addresses/{address}/main', [ProfileController::class, 'setMainAddress'])->name('profile.addresses.main');

    // Vendor Onboarding
    Route::get('/vendor/onboarding', [VendorProfileController::class, 'create'])->name('vendor.onboarding');
    Route::post('/vendor/onboarding', [VendorProfileController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/pending', [VendorProfileController::class, 'pending'])->name('vendor.pending');
    Route::get('/vendor/rejected', [VendorProfileController::class, 'rejected'])->name('vendor.rejected');

    // Notifications
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/my-dxn-application/{application}', [DxnTeamRequestController::class, 'status'])->name('dxn-distributor.status');
});

Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard.redirect');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);

    // Product Approvals
    Route::patch('items/{item}/approve', [ProductApprovalController::class, 'approve'])->name('items.approve');
    Route::patch('items/{item}/reject', [ProductApprovalController::class, 'reject'])->name('items.reject');

    Route::resource('items', ItemController::class);
    Route::delete('items/images/{image}', [ItemController::class, 'deleteImage'])->name('items.delete-image');
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::patch('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
    Route::get('reports/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('reports/vendors', [ReportController::class, 'vendors'])->name('reports.vendors');
    Route::get('reports/brands', [ReportController::class, 'brands'])->name('reports.brands');
    Route::get('reports/financials', [ReportController::class, 'financials'])->name('reports.financials');

    Route::resource('reviews', ReviewController::class)->only(['index', 'destroy', 'create', 'store']);
    Route::patch('reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::patch('avatar-options/{avatarOption}/toggle', [AvatarOptionController::class, 'toggle'])->name('avatar-options.toggle');
    Route::resource('avatar-options', AvatarOptionController::class)->except(['show']);

    Route::get('special-requests', [SpecialRequestController::class, 'index'])->name('special-requests.index');
    Route::patch('special-requests/{specialRequest}/status', [SpecialRequestController::class, 'updateStatus'])->name('special-requests.updateStatus');
    Route::post('special-requests/{specialRequest}/assign-offer', [SpecialRequestController::class, 'assignOffer'])->name('special-requests.assign-offer');

    Route::get('vendors/requests', [VendorRequestController::class, 'index'])->name('vendors.requests.index');
    Route::get('vendors/requests/{vendorProfile}', [VendorRequestController::class, 'show'])->name('vendors.requests.show');
    Route::patch('vendors/requests/{vendorProfile}', [VendorRequestController::class, 'update'])->name('vendors.requests.update');
    Route::patch('vendors/requests/{vendorProfile}/subscription', [VendorRequestController::class, 'confirmSubscription'])->name('vendors.requests.confirm-subscription');

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    Route::get('dxn-team-requests', [AdminDxnTeamRequestController::class, 'index'])->name('dxn-team-requests.index');
    Route::get('dxn-team-requests/{dxnTeamRequest}', [AdminDxnTeamRequestController::class, 'show'])->name('dxn-team-requests.show');
    Route::patch('dxn-team-requests/{dxnTeamRequest}', [AdminDxnTeamRequestController::class, 'update'])->name('dxn-team-requests.update');
    Route::delete('dxn-team-requests/{dxnTeamRequest}', [AdminDxnTeamRequestController::class, 'destroy'])->name('dxn-team-requests.destroy');

    Route::get('dxn-sponsor-codes', [DxnSponsorCodeController::class, 'index'])->name('dxn-sponsor-codes.index');
    Route::post('dxn-sponsor-codes', [DxnSponsorCodeController::class, 'store'])->name('dxn-sponsor-codes.store');
    Route::patch('dxn-sponsor-codes/{dxnSponsorCode}', [DxnSponsorCodeController::class, 'update'])->name('dxn-sponsor-codes.update');
    Route::delete('dxn-sponsor-codes/{dxnSponsorCode}', [DxnSponsorCodeController::class, 'destroy'])->name('dxn-sponsor-codes.destroy');

    // Brands Management
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class)->only(['index', 'edit', 'update']);

    // Newsletter Subscribers
    Route::get('subscribers', [NewsletterController::class, 'index'])->name('subscribers.index');
    Route::delete('subscribers/{subscriber}', [NewsletterController::class, 'destroy'])->name('subscribers.destroy');
    Route::post('subscribers/send-mail', [NewsletterController::class, 'sendMail'])->name('subscribers.sendMail');

    // FAQs & Blogs Management
    Route::resource('faqs', AdminFaqController::class);
    Route::resource('blogs', AdminBlogController::class);
    Route::delete('blogs/gallery/{image}', [AdminBlogController::class, 'deleteGalleryImage'])->name('blogs.gallery.destroy');

    // Translations Management
    Route::get('settings/translations', [TranslationController::class, 'index'])->name('settings.translations');
    Route::post('settings/translations', [TranslationController::class, 'update'])->name('settings.translations.update');
});

// Vendor Routes (Protected)
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'vendor'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('vendor.dashboard');
    })->name('dashboard.redirect');

    Route::get('/dashboard', [App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [App\Http\Controllers\Vendor\DashboardController::class, 'orders'])->name('orders');

    // Vendor Products
    Route::resource('items', App\Http\Controllers\Vendor\ItemController::class);

    // Vendor Orders Details
    Route::get('/orders/{order}', [App\Http\Controllers\Vendor\DashboardController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}', [App\Http\Controllers\Vendor\DashboardController::class, 'updateOrderStatus'])->name('orders.update');

    // Vendor Special Requests
    Route::get('special-requests', [App\Http\Controllers\Vendor\SpecialRequestController::class, 'index'])->name('special-requests.index');
    Route::patch('special-requests/{specialRequest}/status', [App\Http\Controllers\Vendor\SpecialRequestController::class, 'updateStatus'])->name('special-requests.updateStatus');
    Route::post('special-requests/{specialRequest}/assign-offer', [App\Http\Controllers\Vendor\SpecialRequestController::class, 'assignOffer'])->name('special-requests.assign-offer');

    // Vendor Brand Management
    Route::get('brand', [App\Http\Controllers\Vendor\BrandController::class, 'edit'])->name('brand.edit');
    Route::put('brand', [App\Http\Controllers\Vendor\BrandController::class, 'update'])->name('brand.update');
});

require __DIR__.'/auth.php';
