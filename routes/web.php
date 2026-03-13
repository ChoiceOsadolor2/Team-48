<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Product;



Route::get('/logout-json', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['success' => true]);
})->name('logout.json');

Route::get('/auth/status', function () {
    $user = Auth::user();

    return response()->json([
        'authenticated' => (bool) $user,
        'is_admin'      => $user && $user->role === 'admin',
        'user'          => $user ? $user->only('id', 'name', 'email') : null,
    ]);
});

Route::get('/user-status', function () {
    $user = Auth::user();

    return response()->json([
        'logged_in' => (bool) $user,
        'user'      => $user ? [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role ?? null,
        ] : null,
    ]);
});

Route::get('/', function () {
    return redirect('/pages/ShopAll.html');
});

Route::get('/dashboard', function () {
    return redirect('/pages/ShopAll.html');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search-json', [ProductController::class, 'search'])->name('products.search.json');
Route::middleware(['auth', 'admin'])->get('/products/create', function () {
    return redirect()->route('admin.products.create');
});
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/search-json', [ProductController::class, 'search'])->name('products.search.json');

Route::post('/chatbot/ask', [App\Http\Controllers\ChatbotController::class, 'ask']);

Route::get('/product', function () {
    return redirect('/products');
});


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    Route::get('/cart/json', [CartController::class, 'json'])
        ->name('cart.json');

    Route::get('/cart/add-json/{product}', [CartController::class, 'add'])
        ->name('cart.add.json');

    Route::get('/cart/remove-json/{product}', [CartController::class, 'removeJson'])
        ->name('cart.remove.json');

    Route::get('/cart/update-json/{product}', [CartController::class, 'update'])
        ->name('cart.update.json');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');

    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrdersController::class, 'cancel'])->name('orders.cancel');

    Route::get('/test-add/{id}', function ($id) {
        \Illuminate\Support\Facades\Session::put('cart', [$id => 1]);
        return redirect('/cart')->with('status', 'Test product added!');
    });
});


Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', function () {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $inStockProducts = Product::where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->orderBy('name')
            ->take(5)
            ->get(['id', 'name', 'stock']);

        $totalOrders = Order::count();
        $processingOrders = Order::where('status', 'processing')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $completedOrders = Order::whereIn('status', ['completed', 'delivered'])->count();
        $totalRevenue = (float) Order::whereNotIn('status', ['cancelled'])->sum('total');
        $averageOrderValue = $totalOrders > 0
            ? (float) Order::whereNotIn('status', ['cancelled'])->avg('total')
            : 0.0;

        $topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->orderBy('name')
            ->take(4)
            ->get(['id', 'name']);

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $faqCount = Faq::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'inStockProducts',
            'outOfStockProducts',
            'lowStockProducts',
            'totalOrders',
            'processingOrders',
            'cancelledOrders',
            'completedOrders',
            'totalRevenue',
            'averageOrderValue',
            'topCategories',
            'recentOrders',
            'faqCount',
        ));
    })->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
        ->name('admin.users.edit');

    Route::put('/admin/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');

    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');

    Route::get('/admin/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])
        ->name('admin.products.index');

    Route::get('/admin/stock', [\App\Http\Controllers\Admin\ProductController::class, 'stock'])
        ->name('admin.products.stock');

    Route::get('/admin/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])
        ->name('admin.products.create');

    Route::post('/admin/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])
        ->name('admin.products.store');

    Route::get('/admin/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])
        ->name('admin.products.edit');

    Route::put('/admin/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])
        ->name('admin.products.update');

    Route::delete('/admin/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])
        ->name('admin.products.destroy');

   
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])
        ->name('admin.orders.show');

    Route::post('/admin/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])
        ->name('admin.orders.cancel');

    Route::get('/admin/faqs', [AdminFaqController::class, 'index'])
        ->name('admin.faqs.index');

    Route::get('/admin/faqs/create', [AdminFaqController::class, 'create'])
        ->name('admin.faqs.create');

    Route::post('/admin/faqs', [AdminFaqController::class, 'store'])
        ->name('admin.faqs.store');

    Route::get('/admin/faqs/{faq}/edit', [AdminFaqController::class, 'edit'])
        ->name('admin.faqs.edit');

    Route::put('/admin/faqs/{faq}', [AdminFaqController::class, 'update'])
        ->name('admin.faqs.update');

    Route::delete('/admin/faqs/{faq}', [AdminFaqController::class, 'destroy'])
        ->name('admin.faqs.destroy');
});



Route::get('/login-json', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->boolean('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid email or password.',
    ], 401);
});



Route::get('/register-json', function (Request $request) {
    $data = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return response()->json(['success' => true]);
});

Route::get('/login', function (Request $request) {
    $intended = $request->query('redirect') ?? url()->previous() ?? url('/');
    return redirect('/pages/login.html?redirect=' . urlencode($intended));
})->name('login');

require __DIR__.'/auth.php';
