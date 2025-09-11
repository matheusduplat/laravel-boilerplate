<?php

use App\Domains\AccreditedNetworks\Http\Controller\AccreditedNetworksController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    Route::get('', [AccreditedNetworksController::class, 'index'])->name('index');
    Route::get('with-pagination', [AccreditedNetworksController::class, 'withPagination'])->name('withPagination');
    Route::get('show/{accreditedNetworks}', [AccreditedNetworksController::class, 'show'])->name('show');
    Route::post('store', [AccreditedNetworksController::class, 'store'])->name('store');
    Route::post('update/{accreditedNetworks}', [AccreditedNetworksController::class, 'update'])->name('update');
    Route::delete('destroy/{accreditedNetworks}', [AccreditedNetworksController::class, 'destroy'])->name('destroy');
    Route::get('restore/{accreditedNetworks}', [AccreditedNetworksController::class, 'restore'])->name('restore');
});
