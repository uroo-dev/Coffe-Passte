<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Kitchen\KitchenController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::get('menu/{qrToken}', [CustomerController::class, 'menu']);
    Route::get('modifiers/{menu}', [CustomerController::class, 'modifiers']);
    Route::post('checkout/{qrToken}', [CustomerController::class, 'checkout']);
    Route::get('order/{order}/track', [CustomerController::class, 'trackOrder']);
    Route::post('order/{order}/pay', [CustomerController::class, 'pay']);
    Route::post('call-cashier/{qrToken}', [CustomerController::class, 'callCashier']);
});

Route::middleware('auth')->group(function () {
    Route::middleware('role:staff_kitchen')->prefix('kitchen')->group(function () {
        Route::get('queue', [KitchenController::class, 'queue']);
        Route::post('order/{order}/start-cooking', [KitchenController::class, 'startCooking']);
        Route::post('order/{order}/finish-cooking', [KitchenController::class, 'finishCooking']);
        Route::get('history', [KitchenController::class, 'history']);
    });

    Route::middleware('role:staff_cashier')->prefix('cashier')->group(function () {
        Route::get('tables', [CashierController::class, 'tables']);
        Route::get('tables/{table}/orders', [CashierController::class, 'tableOrders']);
        Route::get('menus', [CashierController::class, 'menus']);
        Route::post('order', [CashierController::class, 'createOrder']);
        Route::post('order/{order}/confirm-cash', [CashierController::class, 'confirmCashPayment']);
        Route::post('order/{order}/complete', [CashierController::class, 'completeOrder']);
    });

    Route::middleware('role:owner,admin')->prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('tables', [AdminController::class, 'tables']);
        Route::post('tables', [AdminController::class, 'storeTable']);
        Route::post('tables/{table}/generate-qr', [AdminController::class, 'generateQr']);
        Route::delete('tables/{table}', [AdminController::class, 'destroyTable']);
        Route::get('categories', [AdminController::class, 'categories']);
        Route::post('categories', [AdminController::class, 'storeCategory']);
        Route::put('categories/{category}', [AdminController::class, 'updateCategory']);
        Route::delete('categories/{category}', [AdminController::class, 'destroyCategory']);
        Route::get('menus', [AdminController::class, 'menus']);
        Route::post('menus', [AdminController::class, 'storeMenu']);
        Route::put('menus/{menu}', [AdminController::class, 'updateMenu']);
        Route::delete('menus/{menu}', [AdminController::class, 'destroyMenu']);
        Route::get('menus/{menu}/modifiers', [AdminController::class, 'modifierByMenu']);
        Route::post('modifiers', [AdminController::class, 'storeModifier']);
        Route::put('modifiers/{modifier}', [AdminController::class, 'updateModifier']);
        Route::delete('modifiers/{modifier}', [AdminController::class, 'destroyModifier']);
        Route::get('orders', [AdminController::class, 'orders']);
        Route::post('orders/{order}/cancel', [AdminController::class, 'cancelOrder']);
    });
});
