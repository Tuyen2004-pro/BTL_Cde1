<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// ADMIN
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\TableController;
use App\Http\Controllers\admin\OrderController as AdminOrderController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UserController;

// STAFF
use App\Http\Controllers\Staff\OrderController as StaffOrderController;
use App\Http\Controllers\Staff\TableController as StaffTableController;

// SHIPPER
use App\Http\Controllers\Shipper\OrderController as ShipperOrderController;

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('tables', TableController::class);

        Route::resource('users', UserController::class);

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{id}', [AdminOrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    });

/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/', function () {
            return view('staff.dashboard');
        })->name('dashboard');

        // ===== ORDERS =====
        Route::get('orders', [StaffOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [StaffOrderController::class, 'create'])->name('orders.create');
        Route::post('orders/store', [StaffOrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{id}', [StaffOrderController::class, 'show'])->name('orders.show');

        Route::get('orders/{id}/edit', [StaffOrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{id}', [StaffOrderController::class, 'update'])->name('orders.update');

        Route::post('orders/{id}/pay', [StaffOrderController::class, 'pay'])->name('orders.pay');

        // ❌ KHÔNG DÙNG NỮA (có thể giữ lại nhưng đã bị chặn trong controller)
        Route::delete('orders/{id}', [StaffOrderController::class, 'destroy'])->name('orders.destroy');

        // ✅ THÊM ROUTE HỦY ĐƠN (QUAN TRỌNG)
        Route::post('orders/{id}/cancel', [StaffOrderController::class, 'cancel'])
            ->name('orders.cancel');

        // ===== TABLES =====
        Route::get('tables', [StaffTableController::class, 'index'])->name('tables.index');
    });

/*
|--------------------------------------------------------------------------
| SHIPPER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:shipper'])
    ->prefix('shipper')
    ->name('shipper.')
    ->group(function () {

        Route::get('/', function () {
            return view('shipper.dashboard');
        })->name('dashboard');

        Route::get('orders', [ShipperOrderController::class, 'index'])->name('orders');
    });
