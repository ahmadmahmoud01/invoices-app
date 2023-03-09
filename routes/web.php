<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();
// Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Invoices Routes
Route::resource('invoices', InvoiceController::class);
Route::get('/section/{id}', [InvoiceController::class, 'getProduct']);

// view file
Route::get('/view-file/{invoice_number}/{file_name}', [InvoiceAttachmentsController::class, 'viewFile'])->name('file.view');

// download file
Route::get('/download/{invoice_number}/{file_name}', [InvoiceAttachmentsController::class, 'downloadFile'])->name('file.download');

// edit status of payment
Route::get('/invoices/{invoice}/edit-payment-status', [InvoiceController::class, 'editPaymentStatus'])->name('payment.status.edit');

//update status of payment
Route::post('/invoices/{invoice}/update-payment-status', [InvoiceController::class, 'updatePaymentStatus'])->name('payment.status.update');

// paid invoices
Route::get('/paid-invoices', [InvoiceController::class, 'paidInvoices'])->name('invoices.paid');

// unpaid invoices
Route::get('/unpaid-invoices', [InvoiceController::class, 'unpaidInvoices'])->name('invoices.unpaid');

// partially paid invoices
Route::get('/partially-paid-invoices', [InvoiceController::class, 'partiallyPaidInvoices'])->name('invoices.partiallyPaid');

//print invoice
Route::get('/print-invoice/{invoice}', [InvoiceController::class, 'printInvoice'])->name('invoices.print');



// Sections Routes
Route::resource('sections', SectionController::class);

// Product Routes
Route::resource('products', ProductController::class);

// attchment route

    //delete file
Route::resource('invoices-attachments', InvoiceAttachmentsController::class)->only('destroy');

   // add more attachments

Route::post('attachments/add', [InvoiceAttachmentsController::class, 'add'])->name('attachment.add');



Route::get('/{page}', [AdminController::class, 'index']);

