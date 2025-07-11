<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'auth.'], function () {
    include __DIR__ . '/route/auth.php';
});
Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
    include __DIR__ . '/route/user.php';
});
