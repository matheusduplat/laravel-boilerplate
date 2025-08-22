<?php

use App\Domains\Auth\Http\Controller\AuthController;
use App\Domains\Auth\Http\Controller\ForgotPasswordController;
use App\Domains\Auth\Http\Controller\ResetPasswordController;
use App\Domains\Customer\Http\Controller\CustomerController;

use App\Domains\User\Http\Controller\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/verify-code', [AuthController::class, 'verifyCode']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

Route::get('mail/verify/{user_id}', [AuthController::class, 'verifyMail'])->name('verification.verify');
Route::get('mail/resend/{user_id}', [AuthController::class, 'mailResend'])->name('verification.resend');

Route::post('/create-password', [UserController::class, 'createPassword'])->name('create.password');

Route::get('resend-create-password/{user}', [UserController::class, 'resendCreatePassword'])->name('resend.create.password');

Route::group(['middleware' => ['auth:sanctum,customer', 'verified']], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
});


Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
    Route::post('login', [AuthController::class, 'loginCustomer'])->name('login-customer');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmailCustomer']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetCustomer']);

    Route::get('mail/verify/{customer_id}', [AuthController::class, 'verifyMailCustomer'])->name('verification.verify');
    Route::get('mail/resend/{customer_id}', [AuthController::class, 'mailResendCustomer'])->name('verification.resend');

    Route::post('/create-password', [CustomerController::class, 'createPassword'])->name('create.password');
    Route::get('resend-create-password/{customer}', [CustomerController::class, 'resendCreatePassword'])->name('resend.create.password');
});
