<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class SignUpController extends Controller
{
    /**
     * 后台登录
     *
     * @param LoginRequest $request
     * @return false|string
     */
    public function login(LoginRequest $request): false|string
    {
        // 验证码验证
        if (!captcha_api_check($request->captchaCode, $request->captchaKey)) {
            return statusJson(400, false, '验证码错误');
        }

        // 获取账号密码
        $user = $request->only('email', 'password');

        // 验证账号密码
        if (Auth::attempt($user)) {
            // 验证成功后生成一个唯一的 token
            $token = bin2hex(openssl_random_pseudo_bytes(30));

            // token 的过期时间，300 分钟
            $expiresIn = 18000;

            // 将 token 存储到 Redis 中
            $id = Auth::id();
            Redis::set("user:login:$id:token", $token);

            // 设置 token 的过期时间
            Redis::expire("user:login:$id:token", $expiresIn);

            // 获取当前时间并计算 token 过期的具体时间戳
            $expiresAt = now()->addSeconds($expiresIn)->timestamp;

            return statusJson(200, true, 'success', [
                'user_id' => $id,
                'access_token' => $token,
                'expires_at' => $expiresAt
            ]);
        }

        return statusJson(400, false, '账号或密码错误');
    }

    /**
     * 后台登出
     *
     * @param Request $request
     * @return false|string
     */
    public function logout(Request $request): false|string
    {
        // 获取用户 id
        $id = $request->header('user_id');
        try {
            // 删除 Redis 中的 token
            Redis::del("user:login:$id:token");
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }

        return statusJson(200, true, 'success');
    }
}
