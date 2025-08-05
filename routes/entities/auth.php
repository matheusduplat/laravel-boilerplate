<?php

use App\Domains\Auth\Http\Controller\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/verify-code', [AuthController::class, 'verifyCode']);


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
});
