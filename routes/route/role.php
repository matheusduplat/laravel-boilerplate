<?php

use App\Domains\Role\Http\Controller\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('', [RoleController::class, 'index'])->name('index');
    Route::get('with-pagination', [RoleController::class, 'withPagination'])->name('withPagination');
    Route::get('component-select', [RoleController::class, 'componentSelect'])->name('componentSelect');
    Route::get('show/{role}', [RoleController::class, 'show'])->name('show');
    Route::post('store', [RoleController::class, 'store'])->name('store');
    Route::post('update/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('destroy/{role}', [RoleController::class, 'destroy'])->name('destroy');
    Route::get('restore/{role}', [RoleController::class, 'restore'])->name('restore');
});
