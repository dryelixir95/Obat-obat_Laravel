<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->Admin()) {
            return redirect()->route('adminobat.index');
        } elseif (Auth::user()->Super()) {
            return redirect()->route('super.dashboard');
        }
    }else{
        return redirect()->route('guest.index');
    }
});

Route::get('logout', function () {
    auth()->logout();
    Session()->flush();

    return Redirect::to('/');
})->name('logout');

Auth::routes();

Route::prefix('/')->group(function () {
    Route::get('', [CustomerController::class, 'index'])->name('guest.index');
    Route::post('store', [CustomerController::class, 'store'])->name('guest.store')->middleware('web');
});

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

// Transaksi
Route::prefix('Transaksi')->middleware(['auth', 'user-access:Super Admin,Admin'])->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/add', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/show/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::delete('/destroy/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
});

// Penjualan
Route::prefix('Penjualan')->group(function () {
    Route::get('/', [LaporanPenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/show/{tanggal}', [LaporanPenjualanController::class, 'show'])->name('penjualan.show');
});

