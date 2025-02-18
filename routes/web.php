<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SubadminAuthController;

//FOR CSP AGENT AUTH
Route::get('/', [AuthController::class, 'dashboard'])->middleware('auth');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//FOR ADMIN AUTH
Route::prefix('admin')->group(function () {
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->middleware('auth:admin')->name('admin.dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// For SubAdmin Auth
Route::prefix('subadmin')->group(function () {
    Route::get('/register', [SubadminAuthController::class, 'showRegisterForm'])->name('subadmin.register');
    Route::post('/register',[SubadminAuthController::class, 'register']);
    Route::get('/login', [SubadminAuthController::class, 'showLoginForm'])->name('subadmin.login');
    Route::post('/login', [SubadminAuthController::class, 'login']);
    Route::get('/dashboard',[SubadminAuthController::class, 'dashboard'])->middleware('auth:subadmin')->name('subadmin.dashboard');
    Route::post('/logout', [SubadminAuthController::class, 'logout'])->name('subadmin.logout');
});