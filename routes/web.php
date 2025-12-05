<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\ProductController as FrontProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrdersController;
use App\Models\StockLog;
use App\Models\Inventory;


// Public Pages
Route::get('/', fn() => view('home'));
Route::get('/home', fn() => view('home'));
Route::get('/shop', fn() => view('pages.shop'));
Route::get('/about', fn() => view('pages.about'));
Route::get('/contact', fn() => view('pages.contact'));


// Dashboard
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// User Routes
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product Catalogue
    Route::get('/products', [FrontProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [FrontProductController::class, 'show'])->name('products.show');
    Route::get('/search', [FrontProductController::class, 'search'])->name('products.search');

    Route::get('/product', fn() => redirect('/products'));

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');

    // Orders
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
});


// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', function () {
        $lowStock = Inventory::with('product')->where('quantity', '<=', 5)->get();
        return view('admin.dashboard', compact('lowStock'));
    })->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');

    Route::get('/admin/stock-logs', function () {
        $logs = StockLog::with('product')->latest()->get();
        return view('admin.inventory.logs', compact('logs'));
    })->name('inventory.logs');

    Route::resource('admin/categories', CategoryController::class);
    Route::resource('admin/products', AdminProductController::class);

    Route::resource('admin/inventory', InventoryController::class)
        ->only(['index', 'edit', 'update'])
        ->names([
            'index'  => 'inventory.index',
            'edit'   => 'inventory.edit',
            'update' => 'inventory.update',
        ]);
});


require __DIR__.'/auth.php';
