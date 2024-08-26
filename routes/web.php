<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;

//Auth Routes
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/masuk', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

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
Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');