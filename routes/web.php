<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect('/app');
});

Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoice.download');
