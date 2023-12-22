<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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

            // token 的过期时间，30 分钟
            $expiresIn = 1800;

            // 将 token 存储到 Redis 中
            $userId = Auth::id();
            Redis::set("user:login:{$userId}:token", $token);

            // 设置 token 的过期时间
            Redis::expire("user:login:{$userId}:token", $expiresIn);

            // 获取当前时间并计算 token 过期的具体时间戳
            $expiresAt = now()->addSeconds($expiresIn)->timestamp;

            return statusJson(
                200,
                true,
                '登录成功',
                [
                    'user_id' => $userId,
                    'access_token' => $token,
                    'expires_at' => $expiresAt
                ]
            );
        }

        return statusJson(400, false, '账号或密码错误');
    }

    /**
     * 获取用户信息
     */
    public function info(Request $request)
    {
        try {
            // 获取用户信息
            $user = User::where('id', $request->header('user_id'))
                ->first(['name', 'avatar']);
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }

        return statusJson(200, true, '获取成功', [
            'userName' => $user->name,
            'avatar' => env('APP_URL') . $user->avatar,
        ]);
    }
}