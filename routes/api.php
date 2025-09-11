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

Route::group(['as' => 'bill.', 'prefix' => 'bill'], function () {
    include __DIR__ . '/route/bill.php';
});

Route::group(['as' => 'incomeReport.', 'prefix' => 'income-report'], function () {
    include __DIR__ . '/route/incomeReport.php';
});

Route::group(['as' => 'copaticipation.', 'prefix' => 'copaticipation'], function () {
    include __DIR__ . '/route/copaticipation.php';
});

Route::group(['as' => 'digitalWallet.', 'prefix' => 'digital-wallet'], function () {
    include __DIR__ . '/route/digitalWallet.php';
});
Route::group(['as' => 'accreditedNetworks.', 'prefix' => 'accredited-networks'], function () {
    include __DIR__ . '/route/accreditedNetworks.php';
});
Route::group(['as' => 'requestManagement.', 'prefix' => 'request-management'], function () {
    include __DIR__ . '/route/requestManagement.php';
});
Route::group(['as' => 'requestManagementResponse.', 'prefix' => 'request-management-response'], function () {
    include __DIR__ . '/route/requestManagementResponse.php';
});

Route::group(['as' => 'notifications.', 'prefix' => 'notifications'], function () {
    include __DIR__ . '/route/notifications.php';
});
Route::group(['as' => 'contact.', 'prefix' => 'contact'], function () {
    include __DIR__ . '/route/contact.php';
});
