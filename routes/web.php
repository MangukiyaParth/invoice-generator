<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('users/{id}/login-with-invoice', [DashboardController::class, 'loginWithInvoice'])
    ->name('invoice.login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // customer
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{id}/details', [CustomerController::class, 'getDetails'])->name('customers.details');
    // company
    Route::resource('company', CompanyController::class);
    Route::get('/company/{id}/details', [CompanyController::class, 'getDetails'])->name('company.details');
    // invoice
    Route::resource('invoice', InvoiceController::class);
    Route::get('/invoice/download/{id}', [InvoiceController::class, 'downloadPDF'])->name('invoice.pdf');
    Route::delete('/invoice/item/{id}', [InvoiceController::class, 'deleteItem'])->name('invoice.item.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
