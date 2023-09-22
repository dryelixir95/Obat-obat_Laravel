<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->Admin()) {
            return redirect()->route('obat.index');
        } elseif (Auth::user()->Super()) {
            return redirect()->route('super.dashboard');
        }
    }
    return redirect()->route('login');
});

Route::get('logout', function () {
    auth()->logout();
    Session()->flush();

    return Redirect::to('/');
})->name('logout');

Auth::routes();

// // auth admin
// Route::middleware(['auth', 'user-access:Admin'])->group(function () {
//     Route::get('/Admin/Obat', [HomeController::class, 'admin'])->name('admin.home');
// });

// superuser dashboard
Route::middleware(['auth', 'user-access:Super Admin'])->group(function () {
    Route::get('/SuperAdmin/Dashboard', [HomeController::class, 'super'])->name('super.dashboard');
});

Route::prefix('Admin/Obat')->middleware(['auth', 'user-access:Admin'])->group(function () {
    Route::get('/', [ObatController::class, 'indexAdmin'])->name('adminobat.index');
    Route::get('/add', [ObatController::class, 'create'])->name('adminobat.create');
    Route::post('/store', [ObatController::class, 'store'])->name('adminobat.store');
});

Route::prefix('SuperAdmin/Obat')->middleware(['auth', 'user-access:Super Admin'])->group(function () {
    Route::get('/', [ObatController::class, 'indexSuper'])->name('superobat.index');
    Route::get('/add', [ObatController::class, 'create'])->name('superobat.create');
    Route::post('/store', [ObatController::class, 'store'])->name('superobat.store');
    Route::get('/edit/{obat}', [ObatController::class, 'edit'])->name('superobat.edit');
    Route::put('/update/{obat}', [ObatController::class, 'update'])->name('superobat.update');
    Route::delete('/destroy/{obat}', [ObatController::class, 'destroy'])->name('superobat.destroy');
});

Route::prefix('SuperAdmin/User')->middleware(['auth', 'user-access:Super Admin'])->group(function () {
    Route::get('/', [UserController::class, 'indexSuper'])->name('user.index');
    Route::get('/add', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

// Penjualan
Route::prefix('SuperAdmin/User')->middleware(['auth', 'user-access:Super Admin'])->group(function () {
    Route::get('/', [UserController::class, 'indexSuper'])->name('user.index');
    Route::get('/add', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::prefix('SuperAdmin/User')->middleware(['auth', 'user-access:Admin'])->group(function () {
    Route::get('/', [UserController::class, 'indexSuper'])->name('user.index');
    Route::get('/add', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::get('/transaction', [TransactionController::class, 'index']);
Route::post('/transaction-add', [TransactionController::class, 'transactionAdd']);
