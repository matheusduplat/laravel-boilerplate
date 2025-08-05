<?php

use App\Domains\User\Http\Controller\UserController;
use Illuminate\Support\Facades\Route;



Route::get('/', [UserController::class, 'index'])->name('index');
Route::group(['middleware' => 'auth:sanctum', 'verified'], function () {
    Route::post('/store', [UserController::class, 'store'])->name('store');
});
