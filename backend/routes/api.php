<?php

use App\Http\Controllers\DriverController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/driver',[DriverController::class, 'show']);
    Route::post('/driver',[DriverController::class, 'update']);
    Route::get('/driver/trips',[DriverController::class, 'trips']);

    Route::post('/trip',[\App\Http\Controllers\TripController::class, 'store']);

    Route::get('/trip/{trip}', [\App\Http\Controllers\TripController::class, 'show'])->missing(function () {
        return response(['message' => 'Cannot find this trip'],404);
    });

    Route::get('/trip', [\App\Http\Controllers\TripController::class, 'current']);

    Route::post('/trip/{trip}/accept', [\App\Http\Controllers\TripController::class, 'accept'])->missing(function () {
        return response(['message' => 'Cannot find this trip'],404);
    });
    // cancel trip
    Route::delete('/trip/{trip}/cancel', [\App\Http\Controllers\TripController::class, 'cancel'])->missing(function () {
        return response(['message' => 'Cannot find this trip'],404);
    });
    Route::post('/trip/{trip}/start', [\App\Http\Controllers\TripController::class, 'start'])->missing(function () {
        return response(['message' => 'Cannot find this trip'],404);
    });
    Route::post('/trip/{trip}/end', [\App\Http\Controllers\TripController::class, 'end'])->missing(function () {
        return response(['message' => 'Cannot find this trip'],404);
    });
    Route::post('/trip/{trip}/location', [\App\Http\Controllers\TripController::class, 'location'])->missing(function () {
        return response(['message' => 'Cannot find this trip'],404);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('/login',[\App\Http\Controllers\AuthController::class, 'submit']);
Route::post('/login/verify',[\App\Http\Controllers\AuthController::class, 'verify']);
