<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    // return view('welcome');
    return view('under-development');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.dashboard');
    // return 'customer Page';
});

Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::get('/customer', [CategoryController::class, 'index'])->name('Customer');
    // return 'seller Page';
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner.dashboard');

    return 'owner Page';
});

require __DIR__.'/auth.php';
