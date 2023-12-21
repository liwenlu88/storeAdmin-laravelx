<?php

namespace App\Http\Controllers;

class CaptchaCodeController extends Controller
{
    /**
     * 获取验证码
     *
     * @return false|string
     */
    public function captcha(): false|string
    {
        return statusResponse(200, true, '验证码获取成功', [
            'url' => app('captcha')->create('default', true)
        ]);
    }
}