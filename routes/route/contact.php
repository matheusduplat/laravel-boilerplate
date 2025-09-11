<?php

use App\Domains\Contact\Http\Controller\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('store', [ContactController::class, 'store'])->name('store');

Route::group(['middleware' => 'auth:sanctum,customer'], function () {
    Route::get('', [ContactController::class, 'index'])->name('index');
    Route::get('with-pagination', [ContactController::class, 'withPagination'])->name('withPagination');
    Route::get('show/{contact}', [ContactController::class, 'show'])->name('show');
    Route::post('update/{contact}', [ContactController::class, 'update'])->name('update');
    Route::delete('destroy/{contact}', [ContactController::class, 'destroy'])->name('destroy');
    Route::put('restore/{contact}', [ContactController::class, 'restore'])->name('restore');
});
