<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductExitController;
use App\Http\Controllers\ProductEntriesController;
use App\Http\Controllers\ProductExitDetailController;
use App\Http\Controllers\ProductEntryDetailController;



//Auth Routes
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/masuk', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


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

    //Product Exit Routes
    Route::get('/product-exits', [ProductExitController::class, 'index'])->name('product_exits.index');
    Route::post('/product-exits', [ProductExitController::class, 'store'])->name('product_exits.store');
    Route::put('/product-exits/{id}', [ProductExitController::class, 'update'])->name('product_exits.update');
    Route::delete('/product-exits/{id}', [ProductExitController::class, 'destroy'])->name('product_exits.destroy');

    // Rute untuk menampilkan halaman AJAX
    Route::get('/product-exits/ajax', [ProductExitController::class, 'loadAjaxData'])->name('product_exits.ajax');
    Route::post('product_exits/import', [ProductExitController::class, 'import'])->name('product_exits.import');
    Route::get('product_exits/export', [ProductExitController::class, 'export'])->name('product_exits.export');

    // Product Exit Detail Routes
    Route::get('product-exit/{productExitId}/details', [ProductExitDetailController::class, 'index'])->name('productExitDetails.index');
    Route::post('product-exit/{productExitId}/details', [ProductExitDetailController::class, 'store'])->name('productExitDetails.store');
    Route::delete('/product-exit-detail/{id}', [ProductExitDetailController::class, 'destroy'])->name('productExitDetails.destroy');

    Route::get('product-exit-details/{productExit}/export', [ProductExitDetailController::class, 'export'])->name('product-exit-details.export');
    Route::post('product-exit-details/{productExit}/import', [ProductExitDetailController::class, 'import'])->name('product-exit-details.import');
});

