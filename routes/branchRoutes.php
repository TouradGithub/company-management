<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountingBranch\InvoiceController;


Route::get('/invoices/scan-code-qr/{id}', [InvoiceController::class, 'scanCodeQr'])->name('invoices.show.scan-code-qr');
Route::prefix('branch')->middleware(['auth', 'verify.branch' , 'check.company.permissions'])->name('branch.')->group(function () {

    Route::get('journal-entry/index' , [App\Http\Controllers\AccountingBranch\JournalEntryController::class , 'index'])->name('journal-entry.index');
    Route::get('journal-entry/create' , [App\Http\Controllers\AccountingBranch\JournalEntryController::class , 'create'])->name('journal-entry.create');
    Route::get('journal-entry/clone/{id}' , [App\Http\Controllers\AccountingBranch\JournalEntryController::class , 'clone'])->name('journal-entry.clone');
    Route::post('journal-entry/store' , [App\Http\Controllers\AccountingBranch\JournalEntryController::class , 'store'])->name('journal-entry.store');
    Route::post('journal-entry/update' , [App\Http\Controllers\AccountingBranch\JournalEntryController::class , 'update'])->name('journal-entry.update');
    Route::get('/journal-entries/ajax', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'fetchEntries'])->name('journal-entry.fetchEntries');
    Route::delete('/journal-entry/{id}', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'destroy'])->name('journal-entry.destroy');
    Route::delete('/journal-entry-details/{id}', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'destroyEntryDetails'])->name('journal-entry.destroy-entry-details');
    Route::get('/journal-entry/edit/{id}', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'edit'])->name('journal-entry.edit');
    Route::get('/journal-entry/export/pdf', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'exportPdf'])->name('journal-entry.export.pdf');
    Route::get('/journal-entry/single/export/pdf/{id}', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'singleexportPdf'])->name('journal-entry.export.singleexportPdf');
    Route::get('/journal-entry/single/print/pdf/{id}', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'singlePrint'])->name('journal-entry.export.singlePrint');
    Route::get('/journal-entry/export/excel', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'exportExcel'])->name('journal-entry.export.excel');
    Route::post('/journal/import', [App\Http\Controllers\AccountingBranch\JournalEntryController::class, 'import'])->name('journal.import');

//    Route::get('/account-statement/index', [App\Http\Controllers\AccountingBranch\AccountStatementController::class, 'index'])->name('account.statement.index');
//    Route::get('/account-statement', [App\Http\Controllers\AccountingBranch\AccountStatementController::class, 'getStatement'])->name('account.statement.getStatement-by-ajax');
//    Route::get('account-statement/export/excel', [App\Http\Controllers\AccountingBranch\AccountStatementController::class, 'exportExcel'])->name('account.statement.export.excel');
//    Route::get('account-statement/export/pdf', [App\Http\Controllers\AccountingBranch\AccountStatementController::class, 'exportPDF'])->name('account.statement.export.pdf');
//    Route::get('account-statement/print/pdf', [App\Http\Controllers\AccountingBranch\AccountStatementController::class, 'printPDF'])->name('account.statement.print.pdf');



//    Route::resource('/users-company', App\Http\Controllers\AccountingBranch\UserCompanyController::class);
//    Route::delete('/users-company/{id}', [App\Http\Controllers\AccountingBranch\UserCompanyController::class, 'destroy'])->name('users-company.destroy');
    Route::get('/income-statement', [App\Http\Controllers\AccountingBranch\IncomeStatementController::class, 'index'])->name('income.statement.index');
    Route::get('/income-statement-data', [App\Http\Controllers\AccountingBranch\IncomeStatementController::class, 'getIncomeStatementData'])->name('income.statement.data');

    Route::get('/trial-balance', [App\Http\Controllers\AccountingBranch\TrialBalanceController::class, 'index'])->name('trial.balance');
    Route::get('/trial-balance/data', [App\Http\Controllers\AccountingBranch\TrialBalanceController::class, 'getTrialBalance'])->name('trial.balance.data');
    Route::get('Acounting/index' , [App\Http\Controllers\AccountingBranch\HomeController::class , 'index'])->name('accounting.index');







    Route::get('/products/index', [App\Http\Controllers\AccountingBranch\ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [App\Http\Controllers\AccountingBranch\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/fetch', [App\Http\Controllers\AccountingBranch\ProductController::class, 'getProducts'])->name('products.fetch');
    Route::get('/products/edit/{id}', [App\Http\Controllers\AccountingBranch\ProductController::class, 'edit']);
    Route::post('/products/update/{id}', [App\Http\Controllers\AccountingBranch\ProductController::class, 'update']); // Use POST with _method=PUT
    Route::delete('/products/delete/{id}', [App\Http\Controllers\AccountingBranch\ProductController::class, 'destroy']);
    Route::post('/products/import', [App\Http\Controllers\AccountingBranch\ProductController::class, 'importProducts'])->name('products.import');


    Route::get('/customers/index', [App\Http\Controllers\AccountingBranch\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/store', [App\Http\Controllers\AccountingBranch\CustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/storeCustomer', [App\Http\Controllers\AccountingBranch\CustomerController::class, 'storeCustomer'])->name('customers.storeCustomer');
    Route::get('/customers/get', [App\Http\Controllers\AccountingBranch\CustomerController::class, 'getCustomers']);
    Route::delete('/customers/delete/{id}', [App\Http\Controllers\AccountingBranch\CustomerController::class, 'delete'])->name('customers.delete');
    Route::get('/customers/edit/{id}', [App\Http\Controllers\AccountingBranch\CustomerController::class, 'edit']);
    Route::put('/customers/update/{id}', [App\Http\Controllers\AccountingBranch\CustomerController::class, 'update']);

    //suppliers-companys
    Route::get('/suppliers-company/index', [App\Http\Controllers\AccountingBranch\SupplierCompanyController::class, 'index'])->name('suppliers-company.index');
    Route::post('/suppliers-company/store', [App\Http\Controllers\AccountingBranch\SupplierCompanyController::class, 'store'])->name('suppliers-company.store');
    Route::post('/suppliers-company/storeCustomer', [App\Http\Controllers\AccountingBranch\SupplierCompanyController::class, 'store'])->name('suppliers-company.storeCustomer');
    Route::get('/suppliers-company/get', [App\Http\Controllers\AccountingBranch\SupplierCompanyController::class, 'getSuppliers']);
    Route::delete('/suppliers-company/delete/{id}', [App\Http\Controllers\AccountingBranch\SupplierCompanyController::class, 'delete'])->name('suppliers-company.delete');
    Route::get('/suppliers-company/edit/{id}', [App\Http\Controllers\AccountingBranch\SupplierCompanyController::class, 'edit']);
    Route::put('/suppliers-company/update/{id}', [App\Http\Controllers\AccountingBranch\SupplierCompanyController::class, 'update']);

    Route::get('/invoices/sales', [InvoiceController::class, 'sales'])->name('invoices.sales');
    Route::get('/invoices/purchases', [InvoiceController::class, 'purchasePage'])->name('invoices.purchases');
    Route::get('/invoices/sales-returns', [InvoiceController::class, 'salesReturns'])->name('invoices.sales-returns');
    Route::get('/invoices/purchase-returns', [InvoiceController::class, 'purchaseReturns'])->name('invoices.purchase-returns');
    Route::get('/invoices/print/{id}', [InvoiceController::class, 'printInvoice'])->name('invoices.print');
    Route::get('/invoices/edit/{id}', [InvoiceController::class, 'editInvoice'])->name('invoices.edit');


    Route::get('/invoices/index', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/store', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'store'])->name('invoices.store');
    Route::post('/invoices/update', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'updateInvoice'])->name('invoices.updateInvoice');
    Route::post('/invoices/purchases/store', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'purchases'])->name('invoices.purchases.store');
    Route::post('/invoices/sales-returns/store', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'salesReturn'])->name('invoices.salesreturns.store');
    Route::post('/invoices/purchase-returns/store', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'purchaseReturn'])->name('invoices.purchasereturns.store');
    Route::get('/getInvoices', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'getInvoices']);
    Route::get('/invoices/{invoiceNumber}', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'getInvoiceByNumber']);
    Route::get('/invoices/purchases/{invoiceNumber}', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'getPurchaseByNumber']);
    Route::delete('/sales-invoices/{id}', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::patch('/sales-invoices/{id}/status', [App\Http\Controllers\AccountingBranch\InvoiceController::class, 'updateStatus'])->name('invoices.updateStatus');


});


