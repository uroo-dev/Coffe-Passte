<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Kitchen\KitchenController;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

Route::prefix('customer')->group(function () {
    Route::get('landing/{qrToken}', function ($qrToken) {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();
        if (!session()->has('visit_time_' . $table->id)) {
            session(['visit_time_' . $table->id => now()]);
        }
        return view('customer.landing', compact('table'));
    })->name('customer.landing');

    Route::get('menu/{qrToken}', function ($qrToken) {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();
        $categories = Category::with(['menus' => fn($q) => $q->where('is_available', true), 'menus.modifiers'])->get();
        return view('customer.menu', compact('table', 'categories'));
    })->name('customer.menu');

    Route::get('cart/{qrToken}', function ($qrToken) {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();
        return view('customer.cart', compact('table'));
    })->name('customer.cart');

    Route::get('orders/{qrToken}', function ($qrToken) {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();
        $visitTime = session('visit_time_' . $table->id, now()->subHours(2));
        $orders = Order::with('items.menu', 'table')
            ->where('table_id', $table->id)
            ->whereIn('order_status', ['pending', 'cooking', 'completed'])
            ->where('created_at', '>=', $visitTime)
            ->orderByDesc('created_at')
            ->get();
        if (request('json')) {
            return response()->json($orders);
        }
        return view('customer.orders', compact('table', 'orders'));
    })->name('customer.orders');

    Route::get('order/{order}/confirmation', function (Order $order, Illuminate\Http\Request $request) {
        return view('customer.order-confirmation', [
            'order' => $order->load('items.menu', 'table'),
            'qrToken' => $request->query('token'),
        ]);
    })->name('customer.order-confirmation');

    Route::get('payment/{qrToken}', function ($qrToken) {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();
        $visitTime = session('visit_time_' . $table->id, now()->subHours(2));
        $activeOrder = Order::where('table_id', $table->id)
            ->whereIn('order_status', ['pending', 'cooking', 'completed'])
            ->where('created_at', '>=', $visitTime)
            ->latest()
            ->first();
        return view('customer.payment', compact('table', 'activeOrder'));
    })->name('customer.payment');

    Route::get('invoice/{order}', function (Order $order) {
        return view('customer.invoice', ['order' => $order->load('items.menu', 'table')]);
    })->name('customer.invoice');

    Route::get('call-staff/{qrToken}', function ($qrToken) {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();
        return view('customer.call-staff', compact('table'));
    })->name('customer.call-staff');

    Route::get('modifiers/{menu}', [CustomerController::class, 'modifiers']);
    Route::post('checkout/{qrToken}', [CustomerController::class, 'checkout'])->name('customer.checkout');
    Route::get('order/{order}/track', [CustomerController::class, 'trackOrder']);
    Route::post('order/{order}/pay', [CustomerController::class, 'pay'])->name('customer.pay');
    Route::post('call-cashier/{qrToken}', [CustomerController::class, 'callCashier'])->name('customer.call-cashier');
    Route::post('call-staff/{qrToken}', [CustomerController::class, 'callStaff'])->name('customer.call-staff.api');
    Route::get('order/{order}/payment-status', [CustomerController::class, 'paymentStatus'])->name('customer.payment-status');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware('role:staff_cashier,staff_kitchen')->prefix('staff')->group(function () {
        Route::get('dashboard', function () {
            return view('staff.dashboard', ['tables' => Table::all()]);
        })->name('staff.dashboard');

        Route::get('table-statuses', function () {
            return response()->json(Table::select('id', 'table_number', 'status', 'updated_at')->get());
        })->name('staff.table-statuses');

        Route::get('service-requests', function () {
            return view('staff.service-requests');
        })->name('staff.service-requests');

        Route::get('pending-requests', function () {
            $requests = App\Models\ServiceRequest::with('table')
                ->where('status', 'pending')
                ->orderByDesc('created_at')
                ->get()
                ->map(fn($r) => [
                    'id' => $r->id,
                    'table_number' => $r->table->table_number,
                    'type' => $r->label,
                    'created_at' => $r->created_at->diffForHumans(),
                ]);
            return response()->json($requests);
        });

        Route::post('dismiss-request/{serviceRequest}', function (App\Models\ServiceRequest $serviceRequest) {
            $serviceRequest->update(['status' => 'done']);
            return response()->json(['message' => 'Done']);
        });

        Route::get('pos', function () {
            return view('staff.pos', [
                'tables' => Table::all(),
                'menus' => Menu::with('category', 'modifiers')->get(),
            ]);
        })->name('staff.pos');

        Route::get('kitchen', function () {
            return view('staff.kitchen', [
                'orders' => Order::with('table', 'items.menu')
                    ->whereIn('order_status', ['pending', 'cooking'])
                    ->orderBy('created_at')->get(),
            ]);
        })->name('staff.kitchen');

        Route::get('transactions', function () {
            return view('staff.transactions', [
                'orders' => Order::with('table')->orderByDesc('created_at')->paginate(10),
            ]);
        })->name('staff.transactions');

    });

    Route::middleware('role:staff_kitchen')->prefix('kitchen')->group(function () {
        Route::get('queue', [KitchenController::class, 'queue']);
        Route::post('order/{order}/start-cooking', [KitchenController::class, 'startCooking'])->name('staff.kitchen.start');
        Route::post('order/{order}/finish-cooking', [KitchenController::class, 'finishCooking'])->name('staff.kitchen.finish');
        Route::get('history', [KitchenController::class, 'history']);
    });

    Route::middleware('role:staff_cashier')->prefix('cashier')->group(function () {
        Route::get('/', [CashierController::class, 'tables'])->name('staff.cashier');
        Route::get('tables', [CashierController::class, 'tables']);
        Route::get('tables/{table}/orders', [CashierController::class, 'tableOrders'])->name('staff.cashier-orders');
        Route::get('menus', [CashierController::class, 'menus']);
        Route::post('order', [CashierController::class, 'createOrder'])->name('staff.pos.create');
        Route::post('order/{order}/confirm-cash', [CashierController::class, 'confirmCashPayment'])->name('staff.confirm-cash');
        Route::post('order/{order}/complete', [CashierController::class, 'completeOrder'])->name('staff.complete-order');
        Route::get('scan-payment/{order}', [CashierController::class, 'scanPaymentPage'])->name('staff.scan-payment');
        Route::post('scan-payment/{order}', [CashierController::class, 'confirmPaymentFromScan'])->name('staff.confirm-payment');
    });

    Route::middleware('role:owner,admin')->prefix('admin')->group(function () {
        Route::get('dashboard', function () { return view('admin.dashboard'); });
        Route::get('categories', function () { return view('admin.categories'); });
        Route::get('menus', function () { return view('admin.menus'); });
        Route::get('tables', function () { return view('admin.tables'); });
        Route::get('orders', function () { return view('admin.orders'); });

        Route::get('users', [UserController::class, 'index'])->middleware('role:owner');
        Route::get('users/create', [UserController::class, 'create'])->middleware('role:owner');
        Route::post('users', [UserController::class, 'store'])->middleware('role:owner');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('role:owner');

        Route::get('api/dashboard', [AdminController::class, 'dashboard']);
        Route::get('api/tables', [AdminController::class, 'tables']);
        Route::post('api/tables', [AdminController::class, 'storeTable']);
        Route::post('api/tables/{table}/generate-qr', [AdminController::class, 'generateQr']);
        Route::delete('api/tables/{table}', [AdminController::class, 'destroyTable']);
        Route::get('api/categories', [AdminController::class, 'categories']);
        Route::post('api/categories', [AdminController::class, 'storeCategory']);
        Route::put('api/categories/{category}', [AdminController::class, 'updateCategory']);
        Route::delete('api/categories/{category}', [AdminController::class, 'destroyCategory']);
        Route::get('api/menus', [AdminController::class, 'menus']);
        Route::post('api/menus', [AdminController::class, 'storeMenu']);
        Route::put('api/menus/{menu}', [AdminController::class, 'updateMenu']);
        Route::delete('api/menus/{menu}', [AdminController::class, 'destroyMenu']);
        Route::get('api/menus/{menu}/modifiers', [AdminController::class, 'modifierByMenu']);
        Route::post('api/modifiers', [AdminController::class, 'storeModifier']);
        Route::put('api/modifiers/{modifier}', [AdminController::class, 'updateModifier']);
        Route::delete('api/modifiers/{modifier}', [AdminController::class, 'destroyModifier']);
        Route::get('api/orders', [AdminController::class, 'orders']);
        Route::post('api/orders/{order}/cancel', [AdminController::class, 'cancelOrder']);
    });
});
