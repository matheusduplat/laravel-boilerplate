<?php

use App\Domains\DigitalWallet\Http\Controller\DigitalWalletController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    Route::get('', [DigitalWalletController::class, 'index'])->name('index');
    Route::get('with-pagination', [DigitalWalletController::class, 'withPagination'])->name('withPagination');
    Route::get('show/{digitalWallet}', [DigitalWalletController::class, 'show'])->name('show');
    Route::post('store', [DigitalWalletController::class, 'store'])->name('store');
    Route::post('update/{digitalWallet}', [DigitalWalletController::class, 'update'])->name('update');
    Route::delete('destroy/{digitalWallet}', [DigitalWalletController::class, 'destroy'])->name('destroy');
    Route::get('restore/{digitalWallet}', [DigitalWalletController::class, 'restore'])->name('restore');
    Route::get('to-customer', [DigitalWalletController::class, 'toCustomer'])->name('toCustomer');
});
