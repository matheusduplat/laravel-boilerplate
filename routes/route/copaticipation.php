<?php

use App\Domains\Copaticipation\Http\Controller\CopaticipationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    Route::get('', [CopaticipationController::class, 'index'])->name('index');
    Route::get('with-pagination', [CopaticipationController::class, 'withPagination'])->name('withPagination');
    Route::get('show/{copaticipation}', [CopaticipationController::class, 'show'])->name('show');
    Route::post('store', [CopaticipationController::class, 'store'])->name('store');
    Route::post('update/{copaticipation}', [CopaticipationController::class, 'update'])->name('update');
    Route::delete('destroy/{copaticipation}', [CopaticipationController::class, 'destroy'])->name('destroy');
    Route::get('restore/{copaticipation}', [CopaticipationController::class, 'restore'])->name('restore');
    Route::get('to-customer', [CopaticipationController::class, 'toCustomer'])->name('toCustomer');
    Route::get('download/{copaticipation}', [CopaticipationController::class, 'download'])->name('download');
});
