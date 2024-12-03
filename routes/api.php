<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Superadministrator\SuperadministratorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('api');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::middleware(['role_or_permission:superadministrator'])->group(function () {
        Route::apiResource('super', SuperadministratorController::class)->only(['index', 'show', 'update']);
        Route::apiResource('company', CompanyController::class);
        Route::apiResource('manager', ManagerController::class);
        Route::apiResource('employee', EmployeeController::class);
    });

    Route::middleware(['role_or_permission:manager'])->group(function () {
        Route::apiResource('manager', ManagerController::class)->only(['index', 'show']);
        Route::apiResource('employee', EmployeeController::class);
    });
});