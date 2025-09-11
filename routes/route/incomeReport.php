<?php

use App\Domains\IncomeReport\Http\Controller\IncomeReportController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    Route::get('', [IncomeReportController::class, 'index'])->name('index');
    Route::get('with-pagination', [IncomeReportController::class, 'withPagination'])->name('withPagination');
    Route::get('show/{incomeReport}', [IncomeReportController::class, 'show'])->name('show');
    Route::post('store', [IncomeReportController::class, 'store'])->name('store');
    Route::post('update/{incomeReport}', [IncomeReportController::class, 'update'])->name('update');
    Route::delete('destroy/{incomeReport}', [IncomeReportController::class, 'destroy'])->name('destroy');
    Route::get('restore/{incomeReport}', [IncomeReportController::class, 'restore'])->name('restore');
    Route::get('to-customer', [IncomeReportController::class, 'toCustomer'])->name('toCustomer');
    Route::get('download/{incomeReport}', [IncomeReportController::class, 'download'])->name('download');
});
