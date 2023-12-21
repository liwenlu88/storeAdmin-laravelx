<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

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
            // 验证成功后生成一个唯一的 token
            $token = bin2hex(openssl_random_pseudo_bytes(30));

            // token 的过期时间，30 分钟
            $expiresIn = 180;

            // 将 token 存储到 Redis 中
            $userId = Auth::id();
            Redis::set("user:{$userId}:token", $token);

            // 设置 token 的过期时间
            Redis::expire("user:{$userId}:token", $expiresIn);

            // 获取当前时间并计算 token 过期的具体时间戳
            $expiresAt = now()->addSeconds($expiresIn)->timestamp;

            return statusResponse(
                200,
                true,
                '登录成功',
                [
                    'token' => $token,
                    'expiresAt' => $expiresAt
                ]
            );
        }

        return statusResponse(0, false, '账号或密码错误');
    }
}