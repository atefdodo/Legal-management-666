<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\CompanyDocumentController;
use App\Http\Controllers\RentalContractController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

// Main Page Route

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/', [HomePage::class, 'index'])->name('pages-home');
    Route::get('/expiring-toast', [HomePage::class, 'expiringToast']);
    Route::resource('company_documents', CompanyDocumentController::class);
    Route::get('/company-documents-list', [CompanyDocumentController::class, 'list']);
    Route::get('/export-documents/pdf', [CompanyDocumentController::class, 'exportPdf']);
    Route::get('/export-documents/docx', [CompanyDocumentController::class, 'exportDocx']);
    Route::resource('rental_contracts', RentalContractController::class);
    Route::get('/rental-contracts-list', [RentalContractController::class, 'list'])->name('rental-contracts.list');
    Route::get('/export-contracts/pdf', [RentalContractController::class, 'exportPdf'])->name('rental-contracts.export.pdf');
    Route::get('/export-contracts/docx', [RentalContractController::class, 'exportDocx'])->name('rental-contracts.export.docx');
});
