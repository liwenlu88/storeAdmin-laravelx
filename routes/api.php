<?php

use App\Http\Controllers\CaptchaCodeController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
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

// 经过登录中间件验证
Route::middleware([AuthMiddleware::class])->group(function () {
    // 用户退出
    Route::post('/admin/user/logout', [SignUpController::class, 'logout']);
    // 用户信息
    Route::post('/admin/user/info', [UserController::class, 'getUserInfo']);
    // 上传头像
    Route::post('/admin/upload/avatar', [UploadFileController::class, 'uploadAvatar']);
    // 更新用户信息
    Route::post('/admin/user/update', [UserController::class, 'updateUserInfo']);
    // 验证旧密码是否正确
    Route::post('/admin/user/verify_password', [UserController::class, 'verifyPassword']);
    // 更新密码
    Route::post('/admin/user/update_password', [UserController::class, 'updatePassword']);
});