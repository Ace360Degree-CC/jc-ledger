<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExcelLogController;
use App\Http\Controllers\ExcelSchemaController;
use App\Http\Controllers\SubadminAuthController;
use App\Http\Controllers\SubadminExcelLogController;
use App\Http\Controllers\BCLedgerController;
use App\Http\Controllers\SubadminBCLedgerController;
use App\Http\Controllers\CSPBCLedgerController;
use App\Http\Controllers\CSPDocuments;
use App\Http\Controllers\MisDataController;
use App\Http\Controllers\AdminDocuments;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// FOR CSP AGENT AUTH
Route::get('/', [AuthController::class, 'dashboard'])->middleware('auth');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('auth.profile.update');


// BC Ledger routes for CSP
Route::get('/bc-ledger', [CSPBCLedgerController::class, 'index'])->middleware('auth')->name('csp.bc-ledger.index');
Route::post('/bc-ledger/report', [CSPBCLedgerController::class, 'generateReport'])->middleware('auth')->name('csp.bc-ledger.report');
Route::get('/bc-ledger/export-pdf', [CSPBCLedgerController::class, 'exportPdf'])->middleware('auth')->name('csp.bc-ledger.export-pdf');

Route::get('/documents',[CSPDocuments::class,'index'])->middleware('auth')->name('csp.index');
Route::get('/engagement-Certificate',[CSPDocuments::class,'engagementCertificate'])->middleware('auth')->name('csp.engagement.certificate');
Route::get('/csp-id',[CSPDocuments::class,'cspIdentity'])->middleware('auth')->name('csp.id');


Route::post('/request',[CSPDocuments::class,'requestCertificate'])->name('certificate.request');
Route::get('/view/{id}',[CSPDocuments::class,'viewCertificate'] )->name('certificate.view');
Route::get('/download/{id}',[CSPDocuments::class,'downloadCertificate'])->name('certificate.download');



Route::get('/assets/{file}', function ($file) {
    $path = base_path('assets/' . $file);

    if (!File::exists($path)) {
        abort(404);
    }

    return Response::file($path);
});

// FOR ADMIN AUTH
Route::prefix('admin')->group(function () {
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->middleware('auth:admin')->name('admin.dashboard');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/createSubadmin', [AdminAuthController::class, 'showcreateSubadmin'])->middleware('auth:admin')->name('admin.addSubadmin');
    Route::post('/createSubadmin', [AdminAuthController::class, 'createSubadmin'])->middleware('auth:admin')->name('admin.addSubadmin');

    Route::get('/editSubadmin/{id}', [AdminAuthController::class, 'editSubadmin'])->middleware('auth:admin')->name('subadmin.edit');

    Route::post('/updateSubadmin', [AdminAuthController::class, 'updateSubadmin'])->middleware('auth:admin')->name('admin.updateSubadmin');

    Route::post('/deleteSubadmin', [AdminAuthController::class, 'deleteSubadmin'])->middleware('auth:admin')->name('admin.deleteSubadmin');

    Route::get('/subadmins', [AdminAuthController::class, 'showSubadminList'])->middleware('auth:admin')->middleware('auth:admin')->name('admin.allSubadmins');

    Route::get('/myprofile', [AdminAuthController::class, 'showProfile'])->middleware('auth:admin')->name('admin.myprofile');

    // Handle profile updates
    Route::post('/admin/profile/update', [AdminAuthController::class, 'updateProfile'])->name('admin.updateProfile')->middleware('auth:admin');

    // Handle csp agents
    Route::get('/registerCSP', [AdminAuthController::class, 'showRegisterFormCSP'])->middleware('auth:admin')->name('admin.addCSP');
    Route::post('/registerCSP', [AdminAuthController::class, 'registerCSP'])->middleware('auth:admin')->name('admin.registerCSP');

    Route::get('/csps', [AdminAuthController::class, 'showCSPList'])->middleware('auth:admin')->name('admin.csps');
    Route::post('/deleteCSP', [AdminAuthController::class, 'deleteCSP'])->middleware('auth:admin')->name('admin.deleteCSP');
    Route::get('/editCSP/{id}', [AdminAuthController::class, 'editCSP'])->middleware('auth:admin')->name('admin.editCSP');
    Route::post('/updateCSP', [AdminAuthController::class, 'updateCSP'])->middleware('auth:admin')->name('admin.updateCSP');

    // Handle Excel upload file in Admin Side
    Route::get('/excel/upload', [ExcelLogController::class, 'showUploadForm'])->middleware('auth:admin')->name('admin.excel.form');
    Route::post('/excel/upload', [ExcelLogController::class, 'uploadExcel'])->middleware('auth:admin')->name('admin.excel.upload');

    // Handle Excel Log in Admin Side
    Route::get('/records', [ExcelLogController::class, 'index'])->middleware('auth:admin')->name('admin.excel.logs');
    Route::delete('/excel/logs/{id}', [ExcelLogController::class, 'deleteLog'])->middleware('auth:admin')->name('admin.excel.delete');

    // BC Ledger routes
    Route::get('/bc-ledger', [BCLedgerController::class, 'index'])->middleware('auth:admin')->name('bc-ledger.index');
    Route::post('/bc-ledger/report', [BCLedgerController::class, 'generateReport'])->name('bc-ledger.report');
    Route::get('/bc-ledger/export-pdf', [BCLedgerController::class, 'exportPdf'])->name('bc-ledger.export-pdf');



    // CSP Documents routes
    Route::get('/csp/documents/{id}', [App\Http\Controllers\AdminAuthController::class, 'getCspDocuments']);
    Route::post('/updateDocuments', [App\Http\Controllers\AdminAuthController::class, 'updateDocuments'])->name('admin.updateDocuments');
    Route::post('/uploadAgreement', [App\Http\Controllers\AdminAuthController::class, 'uploadAgreement'])->name('admin.uploadAgreement');
    Route::get('/csp/certificate/{cspId}/{certificateId}', [App\Http\Controllers\AdminAuthController::class, 'viewCertificate']);
    Route::get('/csp/agreement/{cspId}', [App\Http\Controllers\AdminAuthController::class, 'viewAgreement']);



    Route::get('/mis/upload', [MisDataController::class, 'create'])->name('mis.create');
    Route::post('/mis/upload', [MisDataController::class, 'store'])->name('mis.store');
    Route::get('/mis', [MisDataController::class, 'index'])->name('mis.index');
    Route::delete('/mis/{id}', [MisDataController::class, 'destroy'])->name('mis.destroy');

    Route::get('/mis/download/{filename}', [MisDataController::class, 'download'])->name('mis.download');


    Route::get('/csp/document/certificate/{koCode}', [AdminDocuments::class, 'BCCertificate'])->name('csp.document.certificate');
    Route::get('/csp/document/{documentType}/{koCode}', [AdminDocuments::class, 'documents'])->name('csp.document');

});

// For SubAdmin Auth
Route::prefix('subadmin')->group(function () {
    Route::get('/register', [SubadminAuthController::class, 'showRegisterForm'])->name('subadmin.register');
    Route::post('/register', [SubadminAuthController::class, 'register']);
    Route::get('/login', [SubadminAuthController::class, 'showLoginForm'])->name('subadmin.login');
    Route::post('/login', [SubadminAuthController::class, 'login']);
    Route::get('/dashboard', [SubadminAuthController::class, 'dashboard'])->middleware('auth:subadmin')->name('subadmin.dashboard');
    Route::post('/logout', [SubadminAuthController::class, 'logout'])->name('subadmin.logout');

    // Handle Excel upload file in Subadmin Side
    Route::get('/excel/upload', [SubAdminExcelLogController::class, 'showUploadForm'])->middleware('auth:subadmin')->name('subadmin.excel.form');
    Route::post('/excel/upload', [SubAdminExcelLogController::class, 'uploadExcel'])->middleware('auth:subadmin')->name('subadmin.excel.upload');

    // Handle Excel Log in Subadmin Side
    Route::get('/records', [SubAdminExcelLogController::class, 'index'])->middleware('auth:subadmin')->name('subadmin.excel.logs');
    Route::delete('/excel/logs/{id}', [SubAdminExcelLogController::class, 'deleteLog'])->middleware('auth:subadmin')->name('subadmin.excel.delete');


    // BC Ledger routes for subadmin
    Route::get('/bc-ledger', [SubadminBCLedgerController::class, 'index'])->middleware('auth:subadmin')->name('subadmin.bc-ledger.index');
    Route::get('/bc-ledger/report', [SubadminBCLedgerController::class, 'generateReport'])->middleware('auth:subadmin')->name('subadmin.bc-ledger.report');
    Route::get('/bc-ledger/export-pdf', [SubadminBCLedgerController::class, 'exportPdf'])->middleware('auth:subadmin')->name('subadmin.bc-ledger.export-pdf');



        // Handle csp agents
        Route::get('/registerCSP', [SubadminAuthController::class, 'showRegisterFormCSP'])->middleware('auth:subadmin')->name('subadmin.addCSP');
        Route::post('/registerCSP', [SubadminAuthController::class, 'registerCSP'])->middleware('auth:subadmin')->name('subadmin.registerCSP');
    
        Route::get('/csps', [SubadminAuthController::class, 'showCSPList'])->middleware('auth:subadmin')->name('subadmin.csps');
        Route::post('/deleteCSP', [SubadminAuthController::class, 'deleteCSP'])->middleware('auth:subadmin')->name('subadmin.deleteCSP');
        Route::get('/editCSP/{id}', [SubadminAuthController::class, 'editCSP'])->middleware('auth:subadmin')->name('subadmin.editCSP');
        Route::post('/updateCSP', [SubadminAuthController::class, 'updateCSP'])->middleware('auth:subadmin')->name('subadmin.updateCSP');


});
