<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Auth\{LoginController, RegisterController};

use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboard,
    CategoryController  as AdminCategory,
    StaffController     as AdminStaff,
};
use App\Http\Controllers\Cashier\{
    DashboardController as CashierDashboard,
    PosController,
    ReportController    as CashierReport,
    OrderController as CashierOrder,
};
use App\Http\Controllers\Warehouse\{
    DashboardController as WarehouseDashboard,
    InventoryController,
    ReportController    as WarehouseReport,
    ShippingController as WarehouseShipping,
};
use App\Http\Controllers\Customer\{
    CatalogController, CartController, CheckoutController, OrderController as CustomerOrder,
};

/* ===== Public storefront ===== */
Route::get('/',                [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

/* ===== Cart (session-based; guests OK) ===== */
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',                       [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}',         [CartController::class, 'add'])->name('add');
    Route::patch('/update',               [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}',    [CartController::class, 'remove'])->name('remove');
});

/* ===== Auth ===== */
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'show'])->name('login');
    Route::post('/login',   [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register',[RegisterController::class, 'store']);
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/* ===== Dashboard redirector ===== */
Route::get('/dashboard', DashboardRedirectController::class)->middleware('auth')->name('dashboard.redirect');

/* ===== Customer area ===== */
Route::middleware(['auth','role:customer'])->prefix('account')->name('customer.')->group(function () {
    Route::get('/checkout',                [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout',               [CheckoutController::class, 'place'])->name('checkout.place');
    Route::get('/checkout/{order}/pay',    [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::post('/checkout/{order}/pay/confirm', [CheckoutController::class, 'confirmManual'])->name('checkout.pay.confirm');
    Route::get('/orders',                  [CustomerOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',          [CustomerOrder::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/pdf',      [CustomerOrder::class, 'pdf'])->name('orders.pdf');
});

/* ===== Payment (Midtrans) ===== */
Route::name('payment.')->group(function () {
    Route::get('/payment/finish',   [App\Http\Controllers\PaymentController::class, 'finish'])->name('finish');
    Route::get('/payment/unfinish', [App\Http\Controllers\PaymentController::class, 'unfinish'])->name('unfinish');
    Route::get('/payment/error',    [App\Http\Controllers\PaymentController::class, 'error'])->name('error');
    Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('notification');
});

/* ===== Admin ===== */
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                     AdminDashboard::class)->name('dashboard');
    Route::resource('categories',       AdminCategory::class)->except(['show']);
    Route::resource('staff',            AdminStaff::class)->except(['show']);
});

/* ===== Cashier ===== */
Route::middleware(['auth','role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/',                     CashierDashboard::class)->name('dashboard');
    Route::get('/pos',                  [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos',                 [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/receipt/{order}',  [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/reports',              [CashierReport::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf',          [CashierReport::class, 'pdf'])->name('reports.pdf');
    // Customer orders (pending/unassigned + my handled orders)
    Route::get('/orders',               [CashierOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',       [CashierOrder::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/confirm',[CashierOrder::class, 'confirm'])->name('orders.confirm');
    Route::get('/orders/{order}/pdf',     [CashierOrder::class, 'pdf'])->name('orders.pdf');
});

/* ===== Warehouse ===== */
Route::middleware(['auth','role:warehouse'])->prefix('warehouse')->name('warehouse.')->group(function () {
    Route::get('/',                              WarehouseDashboard::class)->name('dashboard');
    Route::get('/inventory',                     [InventoryController::class,'index'])->name('inventory.index');
    Route::get('/inventory/new',                 [InventoryController::class,'createProduct'])->name('inventory.create');
    Route::post('/inventory',                    [InventoryController::class,'storeProduct'])->name('inventory.store');
    Route::get('/inventory/{product}/restock',   [InventoryController::class,'restockForm'])->name('inventory.restock.form');
    Route::post('/inventory/{product}/restock',  [InventoryController::class,'restock'])->name('inventory.restock');
    Route::get('/inventory/{product}/edit',       [InventoryController::class,'edit'])->name('inventory.edit');
    Route::put('/inventory/{product}',            [InventoryController::class,'update'])->name('inventory.update');
    Route::get('/inventory/{product}/categories', [InventoryController::class,'editCategories'])->name('inventory.categories');
    Route::post('/inventory/{product}/categories',[InventoryController::class,'syncCategories']);
    Route::get('/reports',                       [WarehouseReport::class,'index'])->name('reports.index');
    Route::get('/reports/pdf',                   [WarehouseReport::class,'pdf'])->name('reports.pdf');
    Route::get('/shipping',                          [WarehouseShipping::class,'index'])->name('shipping.index');
    Route::get('/shipping/{order}/ship',             [WarehouseShipping::class,'ship'])->name('shipping.ship');
    Route::post('/shipping/{order}/ship',            [WarehouseShipping::class,'store'])->name('shipping.store');
    Route::post('/shipping/{order}/delivered',       [WarehouseShipping::class,'delivered'])->name('shipping.delivered');
    Route::post('/shipping/{order}/tracking',        [WarehouseShipping::class,'tracking'])->name('shipping.tracking');
});
