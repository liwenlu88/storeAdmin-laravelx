<?php

use App\Http\Controllers\CaptchaCodeController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\UploadFileController;
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

// 验证码
Route::post('/captcha_code', [CaptchaCodeController::class, 'captcha']);

// 后台登录
Route::post('/admin/user/login', [SignUpController::class, 'login']);

Route::middleware([AuthMiddleware::class])->group(function () {
    // 用户信息
    Route::post('/admin/user/info', [SignUpController::class, 'getUserInfo']);
    // 更新用户信息
    Route::post('/admin/user/update', [SignUpController::class, 'updateUserInfo']);
    // 用户退出
    Route::post('/admin/user/logout', [SignUpController::class, 'logout']);
    // 上传文件
    Route::post('/admin/upload/image', [UploadFileController::class, 'uploadImages']);
});