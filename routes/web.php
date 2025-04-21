<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveController;

use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\CompanyAuthController;
use App\Http\Controllers\Branch\AuthBranchController;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PayrollController;

use App\Http\Controllers\Accounting\InvoiceController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\Accounting\JournalController;

/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


use App\Http\Controllers\CompanyController;
Route::middleware(['auth', 'verify.admin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');

    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::get('/company/index', [CompanyController::class, 'index'])->name('company.index');
    Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
    Route::patch('/companies/{id}/update-status', [CompanyController::class, 'updateStatus'])->name('companies.updateStatus');
});

// Route::get('/company/login', [CompanyAuthController::class, 'showLoginForm'])->name('company.login');
Route::post('/company/login', [CompanyAuthController::class, 'login'])->name('company.login');



Route::middleware(['auth', 'verify.company' ])->group(function () {
    Route::middleware(['verify.company.info'])->group(function () {
    Route::post('/company/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');
    Route::get('/company/dashboard', [CompanyAuthController::class, 'dashboard'])->name('company.dashboard');
    Route::get('/company/dashboard', [CompanyAuthController::class, 'dashboard'])->name('company.dashboard');
    Route::get('/company/payrolls/create', [PayrollController::class, 'create'])->name('company.payrolls.create');
    Route::get('/company/payrolls/index', [PayrollController::class, 'index'])->name('company.payrolls.index');
    Route::post('/company/payrolls/store', [PayrollController::class, 'store'])->name('company.payrolls.store');
    Route::get('/payrolls/delete/{date}', [PayrollController::class, 'deleteByDate'])->name('payrolls.deleteByDate');
    Route::delete('/company/payrolls/delete/{id}/{date}', [PayrollController::class, 'delete'])->name('company.payrolls.stodeletere');
    Route::resource('loans', App\Http\Controllers\LoanController::class);
    Route::resource('deductions',App\Http\Controllers\DeductionController::class);
    Route::get('/company/payrolls/data', [PayrollController::class, 'getPayrollData'])->name('company.payrolls.data');
    Route::post('/company/payrolls/export/pdf', [PayrollController::class, 'exportPdf'])->name('company.payrolls.export.pdf');
    Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
    Route::post('/branches/store', [BranchController::class, 'store'])->name('branches.store');
    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('/branches/{id}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    Route::post('/branches/{id}/update', [BranchController::class, 'update'])->name('branches.update');
    Route::post('/branches/{id}/delete', [BranchController::class, 'destroy'])->name('branches.destroy');
    Route::get('/employees/index', [EmployeeController::class, 'index'])->name('company.employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('company.employees.create');
    Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('company.employees.edit');
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('company.employees.store');
    Route::post('/employees/update/{id}', [EmployeeController::class, 'update'])->name('company.employees.update');
    Route::delete('/employees/delete/{id}', [EmployeeController::class, 'delete'])->name('company.employees.delete');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('company.leaves.store');
    Route::get('/leaves/index', [LeaveController::class, 'index'])->name('company.leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('company.leaves.create');
    Route::get('/branches/{branch}/employees', [EmployeeController::class, 'getEmployeesByBranch'])->name('branches.employees');
    Route::get('/employees-by-branch', [EmployeeController::class, 'getEmployeesByBranchWithRelationShip'])->name('branches.employees.getEmployeesByBranchWithRelationShip');
    // Edit leave form
    Route::get('/leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('company.leaves.edit');
    // Update leave
    Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('company.leaves.update');
    // Delete leave
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('company.leaves.destroy');


    // Overtime Routes
    Route::get('/overtimes/edit/{id}', [OvertimeController::class, 'edit'])->name('company.overtimes.edit');
    Route::delete('/overtimes/destroy/{id}', [OvertimeController::class, 'destroy'])->name('company.overtimes.destroy');
    Route::post('/overtimes/update/{id}', [OvertimeController::class, 'update'])->name('company.overtimes.update');
    Route::get('/overtimes/index', [OvertimeController::class, 'index'])->name('company.overtimes.index');
    Route::get('/overtimes/create', [OvertimeController::class, 'create'])->name('company.overtimes.create');
    Route::post('/overtimes', [OvertimeController::class, 'store'])->name('company.overtimes.store');
    Route::get('/get-employees-by-branch', [OvertimeController::class, 'getEmployeesByBranch']);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
// web.php
    Route::get('/next-number/{parentId}', [App\Http\Controllers\Accounting\AccountsTreeController::class, 'getNextAccountNumber']);


    Route::get('journal-entry/index' , [App\Http\Controllers\Accounting\JournalEntryController::class , 'index'])->name('journal-entry.index');
    Route::get('journal-entry/create' , [App\Http\Controllers\Accounting\JournalEntryController::class , 'create'])->name('journal-entry.create');
    Route::post('journal-entry/store' , [App\Http\Controllers\Accounting\JournalEntryController::class , 'store'])->name('journal-entry.store');
    Route::post('journal-entry/update' , [App\Http\Controllers\Accounting\JournalEntryController::class , 'update'])->name('journal-entry.update');
    Route::get('/journal-entries/ajax', [App\Http\Controllers\Accounting\JournalEntryController::class, 'fetchEntries'])->name('journal-entry.fetchEntries');
    Route::delete('/journal-entry/{id}', [App\Http\Controllers\Accounting\JournalEntryController::class, 'destroy'])->name('journal-entry.destroy');
    Route::delete('/journal-entry-details/{id}', [App\Http\Controllers\Accounting\JournalEntryController::class, 'destroyEntryDetails'])->name('journal-entry.destroy-entry-details');
    Route::get('/journal-entry/edit/{id}', [App\Http\Controllers\Accounting\JournalEntryController::class, 'edit'])->name('journal-entry.edit');
    Route::get('/journal-entry/export/pdf', [App\Http\Controllers\Accounting\JournalEntryController::class, 'exportPdf'])->name('journal-entry.export.pdf');
    Route::get('/journal-entry/single/export/pdf/{id}', [App\Http\Controllers\Accounting\JournalEntryController::class, 'singleexportPdf'])->name('journal-entry.export.singleexportPdf');
    Route::get('/journal-entry/export/excel', [App\Http\Controllers\Accounting\JournalEntryController::class, 'exportExcel'])->name('journal-entry.export.excel');

    Route::get('CostCenter/index' , [App\Http\Controllers\Accounting\CostCenterController::class , 'index'])->name('cost-center.index');
    Route::post('CostCenter/store' , [App\Http\Controllers\Accounting\CostCenterController::class , 'store'])->name('cost-center.store');
    Route::get('/cost-centers/edit/{id}', [App\Http\Controllers\Accounting\CostCenterController::class, 'edit'])->name('cost-center.edit');
    Route::put('/cost-centers/update/{id}', [App\Http\Controllers\Accounting\CostCenterController::class, 'update'])->name('cost-center.update');
    Route::delete('/cost-centers/delete/{id}', [App\Http\Controllers\Accounting\CostCenterController::class, 'destroy'])->name('cost-center.delete');


    Route::get('/account-statement/index', [App\Http\Controllers\Accounting\AccountStatementController::class, 'index'])->name('account.statement.index');
    Route::get('/account-statement', [App\Http\Controllers\Accounting\AccountStatementController::class, 'getStatement'])->name('account.statement.getStatement-by-ajax');
    Route::get('account-statement/export/excel', [App\Http\Controllers\Accounting\AccountStatementController::class, 'exportExcel'])->name('account.statement.export.excel');
    Route::get('account-statement/export/pdf', [App\Http\Controllers\Accounting\AccountStatementController::class, 'exportPDF'])->name('account.statement.export.pdf');


    Route::get('Acounting/index' , [App\Http\Controllers\Accounting\HomeController::class , 'index'])->name('accounting.index');
    Route::get('Acounting/index-table' , [App\Http\Controllers\Accounting\AccountsTreeController::class , 'accountTable'])->name('accounting.index.table');
    Route::get('Acounting/delete/{id}' , [App\Http\Controllers\Accounting\AccountsTreeController::class , 'delete'])->name('accounting.delete');
    Route::get('Acounting/accountsTree' , [App\Http\Controllers\Accounting\AccountsTreeController::class , 'index'])->name('accounting.accountsTree.index');
    Route::get('Acounting/edit/{id}' , [App\Http\Controllers\Accounting\AccountsTreeController::class , 'edit'])->name('accounting.accountsTree.edit');
    Route::post('Acounting/update-account' , [App\Http\Controllers\Accounting\AccountsTreeController::class , 'update'])->name('accounting.accountsTree.update');
    Route::post('Acounting/accountsTree' , [App\Http\Controllers\Accounting\AccountsTreeController::class , 'store'])->name('accounting.accountsTree.store');
    Route::get('/accounting/accounts-tree/filter', [App\Http\Controllers\Accounting\AccountsTreeController::class, 'filterAccounts'])->name('accounting.accountsTree.filter');
    Route::get('/accounting/accountsTree/search', [App\Http\Controllers\Accounting\AccountsTreeController::class, 'search'])->name('accounting.accountsTree.search');

    Route::get('/accounting/export/pdf', [App\Http\Controllers\Accounting\AccountsTreeController::class, 'exportPdf'])->name('accounting.export.pdf');
    Route::get('/accounting/export/excel', [App\Http\Controllers\Accounting\AccountsTreeController::class, 'exportExcel'])->name('accounting.export.excel');


    Route::get('/journals/get', [JournalController::class, 'get']);
    Route::post('/journals/store', [JournalController::class, 'store'])->name('journals.store');
    Route::get('/journals/edit/{id}', [JournalController::class, 'edit']);
    Route::put('/journals/update/{id}', [JournalController::class, 'update']);
    Route::delete('/journals/delete/{id}', [JournalController::class, 'delete']);


    Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');

    Route::resource('/users-company', App\Http\Controllers\Accounting\UserCompanyController::class);
    Route::delete('/users-company/{id}', [App\Http\Controllers\Accounting\UserCompanyController::class, 'destroy'])->name('users-company.destroy');
    Route::get('/income-statement', [App\Http\Controllers\Accounting\IncomeStatementController::class, 'index'])->name('income.statement.index');
    Route::get('/income-statement-data', [App\Http\Controllers\Accounting\IncomeStatementController::class, 'getIncomeStatementData'])->name('income.statement.data');

    Route::get('/trial-balance', [App\Http\Controllers\Accounting\TrialBalanceController::class, 'index'])->name('trial.balance');
    Route::get('/trial-balance/data', [App\Http\Controllers\Accounting\TrialBalanceController::class, 'getTrialBalance'])->name('trial.balance.data');

    Route::get('/products/index', [App\Http\Controllers\Accounting\ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [App\Http\Controllers\Accounting\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/fetch', [App\Http\Controllers\Accounting\ProductController::class, 'getProducts'])->name('products.fetch');
    Route::get('/products/edit/{id}', [App\Http\Controllers\Accounting\ProductController::class, 'edit']);
    Route::post('/products/update/{id}', [App\Http\Controllers\Accounting\ProductController::class, 'update']); // Use POST with _method=PUT
    Route::delete('/products/delete/{id}', [App\Http\Controllers\Accounting\ProductController::class, 'destroy']);

    Route::get('/customers/index', [App\Http\Controllers\Accounting\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/store', [App\Http\Controllers\Accounting\CustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/storeCustomer', [App\Http\Controllers\Accounting\CustomerController::class, 'storeCustomer'])->name('customers.storeCustomer');
    Route::get('/customers/get', [App\Http\Controllers\Accounting\CustomerController::class, 'getCustomers']);
    Route::delete('/customers/delete/{id}', [App\Http\Controllers\Accounting\CustomerController::class, 'delete'])->name('customers.delete');
    Route::get('/customers/edit/{id}', [App\Http\Controllers\Accounting\CustomerController::class, 'edit']);
    Route::put('/customers/update/{id}', [App\Http\Controllers\Accounting\CustomerController::class, 'update']);

    //suppliers-companys
    Route::get('/suppliers-company/index', [App\Http\Controllers\Accounting\SupplierCompanyController::class, 'index'])->name('suppliers-company.index');
    Route::post('/suppliers-company/store', [App\Http\Controllers\Accounting\SupplierCompanyController::class, 'store'])->name('suppliers-company.store');
    Route::post('/suppliers-company/storeCustomer', [App\Http\Controllers\Accounting\SupplierCompanyController::class, 'store'])->name('suppliers-company.storeCustomer');
    Route::get('/suppliers-company/get', [App\Http\Controllers\Accounting\SupplierCompanyController::class, 'getSuppliers']);
    Route::delete('/suppliers-company/delete/{id}', [App\Http\Controllers\Accounting\SupplierCompanyController::class, 'delete'])->name('suppliers-company.delete');
    Route::get('/suppliers-company/edit/{id}', [App\Http\Controllers\Accounting\SupplierCompanyController::class, 'edit']);
    Route::put('/suppliers-company/update/{id}', [App\Http\Controllers\Accounting\SupplierCompanyController::class, 'update']);

    Route::get('/invoices/sales', [InvoiceController::class, 'sales'])->name('invoices.sales');
    Route::get('/invoices/purchases', [InvoiceController::class, 'purchasePage'])->name('invoices.purchases');
    Route::get('/invoices/sales-returns', [InvoiceController::class, 'salesReturns'])->name('invoices.sales-returns');
    Route::get('/invoices/purchase-returns', [InvoiceController::class, 'purchaseReturns'])->name('invoices.purchase-returns');
    Route::get('/invoices/print/{id}', [InvoiceController::class, 'printInvoice'])->name('invoices.print');
    Route::get('/invoices/edit/{id}', [InvoiceController::class, 'editInvoice'])->name('invoices.edit');

    Route::get('/invoices/index', [App\Http\Controllers\Accounting\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [App\Http\Controllers\Accounting\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/store', [App\Http\Controllers\Accounting\InvoiceController::class, 'store'])->name('invoices.store');
    Route::post('/invoices/update', [App\Http\Controllers\Accounting\InvoiceController::class, 'updateInvoice'])->name('invoices.updateInvoice');
    Route::post('/invoices/purchases/store', [App\Http\Controllers\Accounting\InvoiceController::class, 'purchases'])->name('invoices.purchases.store');
    Route::post('/invoices/sales-returns/store', [App\Http\Controllers\Accounting\InvoiceController::class, 'salesReturn'])->name('invoices.salesreturns.store');
    Route::post('/invoices/purchase-returns/store', [App\Http\Controllers\Accounting\InvoiceController::class, 'purchaseReturn'])->name('invoices.purchasereturns.store');
    Route::get('/getInvoices', [App\Http\Controllers\Accounting\InvoiceController::class, 'getInvoices']);
    Route::get('/invoices/{invoiceNumber}', [App\Http\Controllers\Accounting\InvoiceController::class, 'getInvoiceByNumber']);
    Route::get('/invoices/purchases/{invoiceNumber}', [App\Http\Controllers\Accounting\InvoiceController::class, 'getPurchaseByNumber']);
    Route::delete('/sales-invoices/{id}', [App\Http\Controllers\Accounting\InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::patch('/sales-invoices/{id}/status', [App\Http\Controllers\Accounting\InvoiceController::class, 'updateStatus'])->name('invoices.updateStatus');

    Route::get('/additions', [App\Http\Controllers\Accounting\SupplierController::class, 'index'])->name('additions.index');
    Route::post('/link-account-to-customers', [App\Http\Controllers\Accounting\SupplierController::class, 'linkAccountToCustomers']);
    Route::post('/link-cash-register', [App\Http\Controllers\Accounting\SupplierController::class, 'linkCashRegister']);
    Route::post('/link-to-supplier', [App\Http\Controllers\Accounting\SupplierController::class, 'linkToSupplier']);

    Route::get('/settings/index', [App\Http\Controllers\Accounting\SettingsController::class, 'index'])->name('settings.index');


    Route::post('/backup/create', [App\Http\Controllers\Accounting\BackupController::class, 'createBackup'])->name('backup.create');
    Route::get('/suppliers/index', [App\Http\Controllers\Accounting\SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/customers', [App\Http\Controllers\Accounting\SupplierController::class, 'customers'])->name('suppliers.customers');
    Route::post('/suppliers/storeSupplier', [App\Http\Controllers\Accounting\SupplierController::class, 'store'])->name('suppliers.storeSupplier');

    Route::get('/categorie-invoices/index', [App\Http\Controllers\Accounting\CategorieInvoiceController::class, 'index'])->name('categorie-invoices.index');
    Route::post('/categorie-invoices/store', [App\Http\Controllers\Accounting\CategorieInvoiceController::class, 'store'])->name('categorie-invoices.store');

    Route::get('/categorie-invoices/getCategories', [App\Http\Controllers\Accounting\CategorieInvoiceController::class, 'getCategories'])->name('categorie-invoices.getCategories');


    Route::get('/categorie-invoices/categories/{id}', [App\Http\Controllers\Accounting\CategorieInvoiceController::class, 'delete']);
    Route::get('/categorie-invoices/categories/{id}/edit', [App\Http\Controllers\Accounting\CategorieInvoiceController::class, 'edit']);
    Route::put('/categorie-invoices/categories/{id}', [App\Http\Controllers\Accounting\CategorieInvoiceController::class, 'update']);

    Route::get('/session-years', [App\Http\Controllers\Accounting\SessionYearController::class, 'getPage'])->name('session-years.index');
    Route::get('/session-years/get', [App\Http\Controllers\Accounting\SessionYearController::class, 'index']);
    Route::post('/session-years/store', [App\Http\Controllers\Accounting\SessionYearController::class, 'store'])->name('session-years.store');
    Route::get('/session-years/edit/{id}', [App\Http\Controllers\Accounting\SessionYearController::class, 'edit']);
    Route::put('/session-years/update/{id}', [App\Http\Controllers\Accounting\SessionYearController::class, 'update']);
    Route::delete('/session-years/delete/{id}', [App\Http\Controllers\Accounting\SessionYearController::class, 'destroy']);
});
    Route::get('/update-company-information', [App\Http\Controllers\Accounting\ProfileCompanyController::class, 'index'])->name('update.company.info.index');
    Route::put('/companies/update-company-information/{id}', [App\Http\Controllers\Accounting\ProfileCompanyController::class, 'update'])->name('update.company.info.update');

});



Route::post('/branch/login', [AuthBranchController::class, 'login'])->name('branch.login');

Route::prefix('branch')->middleware(['auth', 'verify.branch'])->group(function () {


    Route::get('/dashboard', [AuthBranchController::class, 'dashboard'])->name('branch.dashboard');

    Route::get('overtimes/edit/{id}', [App\Http\Controllers\Branch\OvertimeController::class, 'edit'])->name('branch.overtimes.edit');
    Route::delete('overtimes/destroy/{id}', [App\Http\Controllers\Branch\OvertimeController::class, 'destroy'])->name('branch.overtimes.destroy');
    Route::post('overtimes/update/{id}', [App\Http\Controllers\Branch\OvertimeController::class, 'update'])->name('branch.overtimes.update');
    Route::get('overtimes/index', [App\Http\Controllers\Branch\OvertimeController::class, 'index'])->name('branch.overtimes.index');
    Route::get('overtimes/create', [App\Http\Controllers\Branch\OvertimeController::class, 'create'])->name('branch.overtimes.create');
    Route::post('overtimes', [App\Http\Controllers\Branch\OvertimeController::class, 'store'])->name('branch.overtimes.store');

    Route::post('/leaves', [App\Http\Controllers\Branch\LeaveController::class, 'store'])->name('branch.leaves.store');
    Route::get('/leaves/index', [App\Http\Controllers\Branch\LeaveController::class, 'index'])->name('branch.leaves.index');
    Route::get('/leaves/create', [App\Http\Controllers\Branch\LeaveController::class, 'create'])->name('branch.leaves.create');
    Route::get('/leaves/{leave}/edit', [App\Http\Controllers\Branch\LeaveController::class, 'edit'])->name('branch.leaves.edit');
    Route::put('/leaves/{leave}', [App\Http\Controllers\Branch\LeaveController::class, 'update'])->name('branch.leaves.update');
    Route::delete('/leaves/{leave}', [App\Http\Controllers\Branch\LeaveController::class, 'destroy'])->name('branch.leaves.destroy');

    Route::get('/branches/{branch}/employees', [App\Http\Controllers\Branch\EmployeeController::class, 'getEmployeesByBranch'])->name('branches.employees');
    Route::get('/employees-by-branch', [App\Http\Controllers\Branch\EmployeeController::class, 'getEmployeesByBranchWithRelationShip'])->name('branches.employees.getEmployeesByBranchWithRelationShip');



    Route::get('employees/index', [App\Http\Controllers\Branch\EmployeeController::class, 'index'])->name('branch.employees.index');
    Route::get('employees/create', [App\Http\Controllers\Branch\EmployeeController::class, 'create'])->name('branch.employees.create');
    Route::get('employees/edit/{id}', [App\Http\Controllers\Branch\EmployeeController::class, 'edit'])->name('branch.employees.edit');
    Route::post('employees/store', [App\Http\Controllers\Branch\EmployeeController::class, 'store'])->name('branch.employees.store');
    Route::post('employees/update/{id}', [App\Http\Controllers\Branch\EmployeeController::class, 'update'])->name('branch.employees.update');
    Route::delete('employees/delete/{id}', [App\Http\Controllers\Branch\EmployeeController::class, 'delete'])->name('branch.employees.delete');

    Route::get('payrolls/create', [App\Http\Controllers\Branch\PayrollController::class, 'create'])->name('branch.payrolls.create');
    Route::get('payrolls/index', [App\Http\Controllers\Branch\PayrollController::class, 'index'])->name('branch.payrolls.index');
    Route::post('payrolls/store', [App\Http\Controllers\Branch\PayrollController::class, 'store'])->name('branch.payrolls.store');
    Route::delete('payrolls/delete/{id}/{date}', [App\Http\Controllers\Branch\PayrollController::class, 'delete'])->name('branch.payrolls.stodeletere');
    Route::get('/payrolls/delete/{date}', [App\Http\Controllers\Branch\PayrollController::class, 'deleteByDate'])->name('branch.payrolls.deleteByDate');
    Route::get('payrolls/data', [App\Http\Controllers\Branch\PayrollController::class, 'getPayrollData'])->name('branch.payrolls.data');
    Route::post('payrolls/export/pdf', [App\Http\Controllers\Branch\PayrollController::class, 'exportPdf'])->name('branch.payrolls.export.pdf');


    Route::name('branch.')->group(function () {
        Route::post('loans/store', [App\Http\Controllers\Branch\LoanController::class, 'store'])->name('loans.branch.store');
        Route::delete('loans/delete/{id}', [App\Http\Controllers\Branch\LoanController::class, 'destroy'])->name('loans.branch.delete');
        Route::get('loans/edit/{id}', [App\Http\Controllers\Branch\LoanController::class, 'edit'])->name('loans.edit.com');
        Route::resource('loans', App\Http\Controllers\Branch\LoanController::class);
        Route::resource('deductions', App\Http\Controllers\Branch\DeductionController::class);
    });

});


