<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductEntriesController;
use App\Http\Controllers\ProductEntryDetailController;

//Auth Routes
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/masuk', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['isLogin'])->group(function () {
    //Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //User Routes
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user/tambah', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    //Product Routes
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::post('/product/tambah', [ProductController::class, 'store'])->name('product.store');
    Route::put('/product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::post('/import-excel', [ProductController::class, 'importExcel'])->name('products.import.excel');
    Route::get('/export-excel', [ProductController::class, 'exportExcel'])->name('products.export.excel');

    //Product Entry Routes
    Route::get('/product-entries', [ProductEntriesController::class, 'index'])->name('product_entries.index');
    Route::post('/product-entries/tambah', [ProductEntriesController::class, 'store'])->name('product_entries.store');
    Route::put('/product-entries/{id}', [ProductEntriesController::class, 'update'])->name('product_entries.update');
    Route::delete('/product-entries/{id}', [ProductEntriesController::class, 'destroy'])->name('product_entries.destroy');
    Route::get('product-entries/export', [ProductEntriesController::class, 'export'])->name('product-entries.export');
    Route::post('product-entries/import', [ProductEntriesController::class, 'import'])->name('product-entries.import');

    //Product Entry Detail Routes
    Route::get('/product-entry-details/{productEntryId}', [ProductEntryDetailController::class, 'index'])->name('product-entry-details.index');
    Route::get('/product-entry-details/load-data/{productEntryId}', [ProductEntryDetailController::class, 'loadData'])->name('product-entry-details.loadData');
    Route::post('/product-entry-details', [ProductEntryDetailController::class, 'store'])->name('product-entry-details.store');
    Route::delete('/product-entry-details/{id}', [ProductEntryDetailController::class, 'destroy']);
    Route::get('product-entry/export/{id}', [ProductEntryDetailController::class, 'export'])->name('product-entry.export');
    Route::post('product-entry/import/{id}', [ProductEntryDetailController::class, 'import'])->name('product-entry.import');

});

