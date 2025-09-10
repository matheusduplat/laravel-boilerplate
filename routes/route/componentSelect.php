<?php

use App\Domains\Customer\Http\Controller\CustomerController;
use App\Domains\Employee\Http\Controller\EmployeeController;
use App\Domains\Permission\Http\Controller\PermissionController;
use App\Domains\Role\Http\Controller\RoleController;
use App\Domains\State\Http\Controller\StateController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('role', [RoleController::class, 'componentSelect'])->name('role');
    Route::get('permission', [PermissionController::class, 'componentSelect'])->name('permission');
    Route::get('customer', [CustomerController::class, 'componentSelect'])->name('customer');
    Route::get('state', [StateController::class, 'componentSelect'])->name('state');
    Route::get('employee', [EmployeeController::class, 'componentSelect'])->name('employee');
});
