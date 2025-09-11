<?php

use App\Domains\RequestManagement\Http\Controller\RequestManagementController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    Route::get('', [RequestManagementController::class, 'index'])->name('index');
    Route::get('with-pagination', [RequestManagementController::class, 'withPagination'])->name('withPagination');
    Route::get('show/{requestManagement}', [RequestManagementController::class, 'show'])->name('show');
    Route::post('store', [RequestManagementController::class, 'store'])->name('store');
    Route::post('update/{requestManagement}', [RequestManagementController::class, 'update'])->name('update');
    Route::delete('destroy/{requestManagement}', [RequestManagementController::class, 'destroy'])->name('destroy');
    Route::get('restore/{requestManagement}', [RequestManagementController::class, 'restore'])->name('restore');
    Route::get('to-customer', [RequestManagementController::class, 'toCustomer'])->name('toCustomer');
});
