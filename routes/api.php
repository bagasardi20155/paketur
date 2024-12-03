<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('api');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('api');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');