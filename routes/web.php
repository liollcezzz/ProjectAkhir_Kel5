<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Auth\{LoginController, RegisterController};

use App\Http\Controllers\Customer\{
    CatalogController, CartController, CheckoutController, OrderController as CustomerOrder,
};

/* ===== Auth ===== */
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'show'])->name('login');
    Route::post('/login',   [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register',[RegisterController::class, 'store']);
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/* ===== Customer area ===== */
Route::middleware(['auth','role:customer'])->prefix('account')->name('customer.')->group(function () {
    Route::get('/checkout',           [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout',          [CheckoutController::class, 'place'])->name('checkout.place');
    Route::get('/orders',             [CustomerOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',     [CustomerOrder::class, 'show'])->name('orders.show');

    // RUTE BARU UNTUK CUSTOMER VIEW 
    Route::get('/customer-view', function () {
        return view('customer_view');
    })->name('view.index');
});
