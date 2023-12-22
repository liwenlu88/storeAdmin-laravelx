<?php

use App\Http\Controllers\SignUpController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// AdminLogin
Route::post('/admin/user/login', [SignUpController::class, 'login']);

// 用户信息
Route::middleware([AuthMiddleware::class])->group(function () {
    Route::post('/admin/user/info', [SignUpController::class, 'info']);
});