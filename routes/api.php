<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'auth.'], function () {
    include __DIR__ . '/route/auth.php';
});

Route::group(['as' => 'employee.', 'prefix' => 'employee'], function () {
    include __DIR__ . '/route/employee.php';
});

Route::group(['as' => 'role.', 'prefix' => 'role'], function () {
    include __DIR__ . '/route/role.php';
});

Route::group(['as' => 'componentselect.', 'prefix' => 'component-select'], function () {
    include __DIR__ . '/route/componentSelect.php';
});

Route::group(['as' => 'customer.', 'prefix' => 'customer'], function () {
    include __DIR__ . '/route/customer.php';
});

Route::group(['as' => 'notifications.', 'prefix' => 'notifications'], function () {
    include __DIR__ . '/route/notifications.php';
});
