<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class AuthMiddleware
{
    /**
     * 登陆状态验证中间件
     */
    public function handle(Request $request, Closure $next)
    {
        // 获取请求头中的 access_token 和 user_id -- 作为key来验证
        $accessToken = $request->header('access_token');
        $tokenKey = $request->header('user_id');

        // 判断 token 是否存在 或者 token 是否正确
        if (!$accessToken || (Redis::get("user:login:{$tokenKey}:token") != $accessToken)) {
            return statusResponse(401, false, '请先登录');
        }

        // 存在则刷新token过期时间 30分钟
        Redis::expire("user:login:{$tokenKey}:token", 1800);

        // 存在则放行
        return $next($request);
    }
}