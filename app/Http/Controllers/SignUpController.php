<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class SignUpController extends Controller
{
    public function login(LoginRequest $request)
    {
        // 验证码验证
        if (!captcha_api_check($request->captchaCode, $request->captchaKey)) {
            return statusResponse(0, false, '验证码错误');
        }

        // 获取账号密码
        $user = $request->only('email', 'password');

        // 验证账号密码
        if (Auth::attempt($user)) {
            // 记录登录信息
            // xxx
            return statusResponse(200, true, '登录成功');
        }

        return statusResponse(0, false, '账号或密码错误');
    }
}