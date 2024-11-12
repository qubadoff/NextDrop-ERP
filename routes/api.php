<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\Auth\AuthController;
use App\Http\Controllers\api\v1\Employee\EmployeeController;


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::prefix('employee')->group(function () {
        Route::prefix('attendance')->group(function () {
            Route::get('/list', [EmployeeController::class, 'list']);
            Route::post('/sendAttendance', [EmployeeController::class, 'sendAttendance']);
        });
        Route::prefix('penal')->group(function () {
            Route::get('/penalList', [EmployeeController::class, 'penalList']);
        });
        Route::prefix('award')->group(function () {
            Route::get('/awardList', [EmployeeController::class, 'awardList']);
        });
    })->middleware('auth:sanctum');
});
