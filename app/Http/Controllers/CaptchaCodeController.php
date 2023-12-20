<?php

namespace App\Http\Controllers;

class CaptchaCodeController extends Controller
{
    public function captcha(): false|string
    {
        return json_encode([
            'status_code' => '200',
            'message' => '验证码获取成功',
            'url' => app('captcha')->create('default', true)
        ]);
    }
}