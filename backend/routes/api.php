<?php

use App\Http\Controllers\DriverController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/driver',[DriverController::class, 'show']);
    Route::post('/driver',[DriverController::class, 'update']);

    Route::post('/trip',[\App\Http\Controllers\TripController::class, 'store']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('/login',[\App\Http\Controllers\AuthController::class, 'submit']);
Route::post('/login/verify',[\App\Http\Controllers\AuthController::class, 'verify']);
