<?php

use App\Http\Controllers\CaptchaCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// CSRF Token
Route::get('/csrf/token', function () {
    return csrf_token();
});

// 验证码
Route::get('/captcha_code', [CaptchaCodeController::class, 'captcha']);
