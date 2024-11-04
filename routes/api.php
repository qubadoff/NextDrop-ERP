<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\Auth\AuthController;


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });
});
