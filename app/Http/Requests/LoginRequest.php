<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * 确定用户是否有权限发出此请求。
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // 这里默认为 true，表示所有用户都可以发出此请求
    }

    /**
     * 获取应用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|max:18',
            'captchaCode' => 'required|size:4'
        ];
    }

    /**
     * 获取已定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'email.exists' => '账户不存在',
            'password.required' => '密码不能为空',
            'password.min' => '密码长度不能小于8位',
            'password.max' => '密码长度不能大于18位',
            'captchaCode.required' => '验证码不能为空',
            'captchaCode.size' => '验证码长度不正确',
        ];
    }
}