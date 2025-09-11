<?php

use App\Domains\Employee\Http\Controller\EmployeeController;
use App\Domains\Role\Http\Controller\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::get('', [EmployeeController::class, 'index'])->name('index');
    Route::get('/with-pagination', [EmployeeController::class, 'withPagination'])->name('withPagination');
    Route::get('/show/{employee}', [EmployeeController::class, 'show'])->name('show');
    Route::post('/store', [EmployeeController::class, 'store'])->name('store');
    Route::post('/update/{employee}', [EmployeeController::class, 'update'])->name('update');
    Route::delete('/destroy/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    Route::post('/perfil/{employee}', [EmployeeController::class, 'perfil'])->name('perfil');
    Route::put('restore/{employee}', [EmployeeController::class, 'restore'])->name('restore');
});
