<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SubadminAuthController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExcelSchemaController;
use App\Http\Controllers\ExcelLogController;
use App\Http\Controllers\SubadminExcelLogController;

//FOR CSP AGENT AUTH
Route::get('/', [AuthController::class, 'dashboard'])->middleware('auth');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('auth.profile.update');


Route::get('/assets/{file}', function ($file) {
    $path = base_path('assets/' . $file);

    if (!File::exists($path)) {
        abort(404);
    }

    return Response::file($path);
});


//FOR ADMIN AUTH
Route::prefix('admin')->group(function () {
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->middleware('auth:admin')->name('admin.dashboard');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/createSubadmin',[AdminAuthController::class, 'showcreateSubadmin'])->middleware('auth:admin')->name('admin.addSubadmin');
    Route::post('/createSubadmin',[AdminAuthController::class, 'createSubadmin'])->middleware('auth:admin')->name('admin.addSubadmin');

    Route::get('/editSubadmin/{id}', [AdminAuthController::class, 'editSubadmin'])->middleware('auth:admin')->name('subadmin.edit');

    Route::post('/updateSubadmin',[AdminAuthController::class, 'updateSubadmin'])->middleware('auth:admin')->name('admin.updateSubadmin');

    Route::post('/deleteSubadmin',[AdminAuthController::class, 'deleteSubadmin'])->middleware('auth:admin')->name('admin.deleteSubadmin');

    Route::get('/subadmins', [AdminAuthController::class, 'showSubadminList'])->middleware('auth:admin')->middleware('auth:admin')->name('admin.allSubadmins');

    Route::get('/myprofile', [AdminAuthController::class, 'showProfile'])->middleware('auth:admin')->name('admin.myprofile');

    // Handle profile updates
    Route::post('/admin/profile/update', [AdminAuthController::class, 'updateProfile'])->name('admin.updateProfile')->middleware('auth:admin');


    // Handle csp agents
    Route::get('/registerCSP',[AdminAuthController::class, 'showRegisterFormCSP'])->middleware('auth:admin')->name('admin.addCSP');
    Route::post('/registerCSP',[AdminAuthController::class, 'registerCSP'])->middleware('auth:admin')->name('admin.registerCSP');

    Route::get('/csps', [AdminAuthController::class, 'showCSPList'])->middleware('auth:admin')->name('admin.csps');

    Route::post('/deleteCSP',[AdminAuthController::class, 'deleteCSP'])->middleware('auth:admin')->name('admin.deleteCSP');


    Route::get('/editCSP/{id}', [AdminAuthController::class, 'editCSP'])->middleware('auth:admin')->name('admin.editCSP');

    Route::post('/updateCSP',[AdminAuthController::class, 'updateCSP'])->middleware('auth:admin')->name('admin.updateCSP');


    //Handle Excel upload file in Admin Side
    Route::get('/excel/upload', [ExcelLogController::class, 'showUploadForm'])->middleware('auth:admin')->name('admin.excel.form');
    Route::post('/excel/upload', [ExcelLogController::class, 'uploadExcel'])->middleware('auth:admin')->name('admin.excel.upload');

    //Handle Excel Log in Admin Side
    Route::get('/records', [ExcelLogController::class, 'index'])->middleware('auth:admin')->name('admin.excel.logs');
    Route::delete('/excel/logs/{id}', [ExcelLogController::class, 'deleteLog'])->middleware('auth:admin')->name('admin.excel.delete');

});

// For SubAdmin Auth
Route::prefix('subadmin')->group(function () {
    Route::get('/register', [SubadminAuthController::class, 'showRegisterForm'])->name('subadmin.register');
    Route::post('/register',[SubadminAuthController::class, 'register']);
    Route::get('/login', [SubadminAuthController::class, 'showLoginForm'])->name('subadmin.login');
    Route::post('/login', [SubadminAuthController::class, 'login']);
    Route::get('/dashboard',[SubadminAuthController::class, 'dashboard'])->middleware('auth:subadmin')->name('subadmin.dashboard');
    Route::post('/logout', [SubadminAuthController::class, 'logout'])->name('subadmin.logout');

    //Handle Excel upload file in Subadmin Side
    Route::get('/excel/upload', [SubAdminExcelLogController::class, 'showUploadForm'])->middleware('auth:subadmin')->name('subadmin.excel.form');
    Route::post('/excel/upload', [SubAdminExcelLogController::class, 'uploadExcel'])->middleware('auth:subadmin')->name('subadmin.excel.upload');

    //Handle Excel Log in Subadmin Side
    Route::get('/records', [SubAdminExcelLogController::class, 'index'])->middleware('auth:subadmin')->name('subadmin.excel.logs');
    Route::delete('/excel/logs/{id}', [SubAdminExcelLogController::class, 'deleteLog'])->middleware('auth:subadmin')->name('subadmin.excel.delete');


});