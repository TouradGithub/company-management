<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\CompanyAuthController;

// Route::get('/company/login', [CompanyAuthController::class, 'showLoginForm'])->name('company.login');
Route::post('/company/login', [CompanyAuthController::class, 'login'])->name('company.login');
Route::post('/company/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');


Route::get('/company/dashboard', [CompanyAuthController::class, 'dashboard'])->name('company.dashboard');


use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;

Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
Route::post('/branches/store', [BranchController::class, 'store'])->name('branches.store');
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/branches/{id}/edit', [BranchController::class, 'edit'])->name('branches.edit');
Route::post('/branches/{id}/update', [BranchController::class, 'update'])->name('branches.update');
Route::post('/branches/{id}/delete', [BranchController::class, 'destroy'])->name('branches.destroy');



Route::get('/employees/index', [EmployeeController::class, 'index'])->name('company.employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('company.employees.create');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('company.employees.store');
use App\Http\Controllers\LeaveController;


Route::post('/leaves', [LeaveController::class, 'store'])->name('company.leaves.store');
Route::get('/leaves/index', [LeaveController::class, 'index'])->name('company.leaves.index');
Route::get('/leaves/create', [LeaveController::class, 'create'])->name('company.leaves.create');

Route::get('/branches/{branch}/employees', [EmployeeController::class, 'getEmployeesByBranch'])->name('branches.employees');

// Edit leave form
Route::get('/leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('company.leaves.edit');

// Update leave
Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('company.leaves.update');

// Delete leave
Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('company.leaves.destroy');


use App\Http\Controllers\OvertimeController;
// Overtime Routes
Route::get('/overtimes/edit/{id}', [OvertimeController::class, 'edit'])->name('company.overtimes.edit');
Route::get('/overtimes/destroy/{id}', [OvertimeController::class, 'destroy'])->name('company.overtimes.destroy');
Route::get('/overtimes/index', [OvertimeController::class, 'index'])->name('company.overtimes.index');
Route::get('/overtimes/create', [OvertimeController::class, 'create'])->name('company.overtimes.create');
Route::post('/overtimes', [OvertimeController::class, 'store'])->name('company.overtimes.store');


Route::get('/get-employees-by-branch', [OvertimeController::class, 'getEmployeesByBranch']);

use App\Http\Controllers\CategoryController;



Route::resource('categories', CategoryController::class);
use App\Http\Controllers\UserController;

Route::resource('users', UserController::class);


use App\Http\Controllers\LoanController;
use App\Http\Controllers\DeductionController;

Route::resource('loans', LoanController::class);
Route::resource('deductions', DeductionController::class);
