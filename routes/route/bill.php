<?php

use App\Domains\Bill\Http\Controller\BillController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    Route::get('', [BillController::class, 'index'])->name('index');
    Route::get('with-pagination', [BillController::class, 'withPagination'])->name('withPagination');
    Route::get('show/{bill}', [BillController::class, 'show'])->name('show');
    Route::post('store', [BillController::class, 'store'])->name('store');
    Route::post('update/{bill}', [BillController::class, 'update'])->name('update');
    Route::delete('destroy/{bill}', [BillController::class, 'destroy'])->name('destroy');
    Route::get('restore/{bill}', [BillController::class, 'restore'])->name('restore');
    Route::get('to-customer', [BillController::class, 'toCustomer'])->name('toCustomer');
    Route::get('download/{bill:title_id}', [BillController::class, 'download'])->name('download');
});
