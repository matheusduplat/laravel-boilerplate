<?php

use App\Domains\Customer\Http\Controller\CustomerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('', [CustomerController::class, 'index'])->name('index');
    Route::get('/with-pagination', [CustomerController::class, 'withPagination'])->name('withPagination');
    Route::get('/show/{customer}', [CustomerController::class, 'show'])->name('show');
    Route::post('/store', [CustomerController::class, 'store'])->name('store');
    Route::post('/update/{customer}', [CustomerController::class, 'update'])->name('update');
    Route::delete('/destroy/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    Route::get('restore/{customer}', [CustomerController::class, 'restore'])->name('restore');
    Route::post('update/address/{customer}', [CustomerController::class, 'updateAddress'])->name('updateAddress');
    Route::post('update/profile/{customer}', [CustomerController::class, 'updateProfile'])->name('updateProfile');
    Route::post('update/phone/{customer}', [CustomerController::class, 'updatePhone'])->name('updatePhone');
    Route::post('update/password/{customer}', [CustomerController::class, 'updatePassword'])->name('updatePassword');
});
