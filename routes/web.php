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


use App\Http\Controllers\LoanController;
use App\Http\Controllers\DeductionController;

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

Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
Route::get('/company/index', [CompanyController::class, 'index'])->name('company.index');
Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
Route::patch('/companies/{id}/update-status', [CompanyController::class, 'updateStatus'])->name('companies.updateStatus');
});

// Route::get('/company/login', [CompanyAuthController::class, 'showLoginForm'])->name('company.login');
Route::post('/company/login', [CompanyAuthController::class, 'login'])->name('company.login');



Route::middleware(['auth', 'verify.company'])->group(function () {
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
