<?php

use App\Domains\Notifications\Http\Controller\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum,customer']], function () {
    Route::get('', [NotificationsController::class, 'index'])->name('index');
    Route::put('read/{id}', [NotificationsController::class, 'read'])->name('read');
    Route::put('read-all', [NotificationsController::class, 'readAll'])->name('read-all');
});
