<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TenantController;

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


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('login',  [TenantController::class, 'login'])->name('tenant.login');
Route::get('logout',  [TenantController::class, 'logout'])->name('tenant.logout');
Route::get('/',  [TenantController::class, 'index'])->name('tenant.index')->middleware('auth:user');
Route::post('authenticate', [TenantController::class, 'authenticate'])->name('tenant.authenticate');
Route::get('create',  [TenantController::class, 'create'])->name('tenant.create')->middleware('auth:user');
Route::post('store', [TenantController::class, 'store'])->name('tenant.store')->middleware('auth:user');
Route::post('update', [TenantController::class, 'update'])->name('tenant.update')->middleware('auth:user');
Route::get('delete/{id}', [TenantController::class, 'delete'])->name('tenant.delete')->middleware('auth:user');
Route::get('suspend/{id}', [TenantController::class, 'suspend'])->name('tenant.suspend')->middleware('auth:user');
Route::get('unsuspend/{id}', [TenantController::class, 'unsuspend'])->name('tenant.unsuspend')->middleware('auth:user');
Route::get('edit/{id}', [TenantController::class, 'edit'])->name('tenant.edit')->middleware('auth:user');
Route::get('users',  [AdminController::class, 'index'])->name('tenant.users')->middleware('auth:user');
Route::get('adduser',  [AdminController::class, 'create'])->name('tenant.adduser')->middleware('auth:user');
Route::post('saveuser',  [AdminController::class, 'store'])->name('tenant.saveuser')->middleware('auth:user');
Route::get('edituser/{id}',  [AdminController::class, 'edit'])->name('tenant.edituser')->middleware('auth:user');
Route::post('updateuser',  [AdminController::class, 'update'])->name('tenant.updateuser')->middleware('auth:user');
Route::get('deleteuser/{id}',  [AdminController::class, 'destroy'])->name('tenant.deleteuser')->middleware('auth:user');

Route::fallback(function () {
    return "404 from Central";
});
