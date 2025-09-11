<?php

use App\Domains\RequestManagementResponse\Http\Controller\RequestManagementResponseController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    // Route::get('', [RequestManagementResponseController::class, 'index'])->name('index');
    // Route::get('with-pagination', [RequestManagementResponseController::class, 'withPagination'])->name('withPagination');
    // Route::get('show/{requestManagementResponse}', [RequestManagementResponseController::class, 'show'])->name('show');
    Route::post('store', [RequestManagementResponseController::class, 'store'])->name('store');
    // Route::post('update/{requestManagementResponse}', [RequestManagementResponseController::class, 'update'])->name('update');
    Route::delete('destroy/{requestManagementResponse}', [RequestManagementResponseController::class, 'destroy'])->name('destroy');
    // Route::get('restore/{requestManagementResponse}', [RequestManagementResponseController::class, 'restore'])->name('restore');
});
