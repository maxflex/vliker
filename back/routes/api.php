<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {
    Route::apiResource('users', 'UsersController')->only(['index', 'store']);

    Route::middleware('auth:api')->group(function () {
        Route::prefix('tasks')->group(function () {
            Route::post('next', 'TasksController@next')->name('tasks.next');
            Route::get('check-queue/{task}', 'TasksController@checkQueue')->name('tasks.check-queue');
        });

        Route::apiResources([
            'tasks' => 'TasksController'
        ]);

        Route::apiResource('reports', 'ReportsController')->only(['store']);
    });
});
