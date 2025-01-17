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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\CompanyController;

Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
Route::get('/company/index', [CompanyController::class, 'index'])->name('company.index');
Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
Route::patch('/companies/{id}/update-status', [CompanyController::class, 'updateStatus'])->name('companies.updateStatus');

use App\Http\Controllers\CompanyAuthController;

Route::get('/company/login', [CompanyAuthController::class, 'showLoginForm'])->name('company.login');
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



Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');


use App\Http\Controllers\CategoryController;

Route::resource('categories', CategoryController::class);
use App\Http\Controllers\UserController;

Route::resource('users', UserController::class);
