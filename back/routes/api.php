<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {
    Route::apiResource('users', 'UsersController')->only(['index', 'store']);

    Route::middleware('auth:api')->group(function () {
        Route::prefix('tasks')->group(function () {
            Route::post('next', 'TasksController@next');
            Route::get('check-queue/{task}', 'TasksController@checkQueue');
        });

        Route::apiResource('tasks', 'TasksController');

        Route::prefix('notifications')->group(function () {
            Route::get('/', 'NotificationsController@index');
            Route::get('/see', 'NotificationsController@see');
        });

        Route::post('reports', 'ReportsController@store');
    });
});
