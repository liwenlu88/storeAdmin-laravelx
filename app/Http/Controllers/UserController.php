<?php

namespace App\Http\Controllers;

use App\Models\Authority;
use App\Models\Recycle;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * 获取用户信息
     * @param Request $request
     * @return false|string
     */
    public function getUserInfo(Request $request): false|string
    {
        try {
            $user = User::where('id', $request->header('user_id'))
                ->first(['id', 'name', 'email', 'avatar', 'description']);
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }

        return statusJson(200, true, 'success', [
            'id' => $user->id,
            'name' => $user->name ?: "无名氏",
            'email' => $user->email,
            'avatar' => $user->avatar ? config('app.url') . $user->avatar : config('app.url') . "/default_picture.jpg",
            'description' => $user->description ?: '这个人很懒，什么都没留下',
        ]);
    }

    /**
     * 修改个人信息
     *
     * @param Request $request
     * @return false|string
     */
    public function updateUserInfo(Request $request): false|string
    {
        $userDetail = $request->all();
        try {
            // 修改当前id的用户信息
            User::where('id', $userDetail['id'])->update([
                'name' => $userDetail['name'],
                'avatar' => $userDetail['avatar'],
                'description' => $userDetail['description'],
                'updated_at' => now(),
            ]);
        } catch (Exception $e) {
            return statusJson(400, false, 'error', [$e->getMessage()]);
        }

        return statusJson(200, true, 'success', [
            'id' => $userDetail['id'],
            'name' => $userDetail['name'],
            'email' => $userDetail['email'],
            'avatar' => config('app.url') . $userDetail['avatar'],
            'description' => $userDetail['description']
        ]);
    }

    /**
     * 验证旧密码是否正确
     *
     * @param Request $request
     * @return false|string
     */
    public function verifyPassword(Request $request): false|string
    {
        $userDetail = $request->all();
        // 获取当前用户信息
        $user = User::where('id', $userDetail['id'])->first(['password']);
        // 验证旧密码是否正确
        if (!password_verify($userDetail['oldPassword'], $user->password)) {
            return statusJson(400, false, 'error');
        }

        return statusJson(200, true, 'success');
    }

    /**
     * 修改密码
     *
     * @param Request $request
     * @return false|string
     */
    public function updatePassword(Request $request): false|string
    {
        $userDetail = $request->all();
        try {
            // 验证新密码是否符合规则 新密码不能与旧密码相同 并验证新密码 newPassword 与确认密码 confirmPassword 是否一致
            $validator = Validator::make(
                $userDetail,
                [
                    'newPassword' => 'required|min:6|max:18|different:oldPassword',
                    'confirmPassword' => 'required|same:newPassword',
                ],
                [
                    'newPassword.required' => '新密码不能为空',
                    'newPassword.min' => '新密码不能少于6位',
                    'newPassword.max' => '新密码不能超过18位',
                    'newPassword.different' => '新密码不能与旧密码相同',
                    'confirmPassword.required' => '确认密码不能为空',
                    'confirmPassword.same' => '确认密码与新密码不一致',
                ]
            );
            if ($validator->fails()) {
                return statusJson(400, false, $validator->errors()->first());
            }

            // 修改密码
            User::where('id', $userDetail['id'])->update([
                'password' => bcrypt($userDetail['newPassword']),
            ]);

        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }

        return statusJson(200, true, 'success');
    }

    /**
     * 获取用户列表
     * @param Request $request
     * @param string $name -- 用户名
     * @param string $email -- 邮箱
     * @param int $pageSize -- 每页显示数量
     * @return false|string
     */
    public function getUserList(Request $request, string $name = '', string $email = '', int $pageSize = 10): false|string
    {
        $name = $request->input('name', $name);
        $email = $request->input('email', $email);
        $pageSize = $request->input('page_size', $pageSize);

        try {
            $userList = User::select('id', 'name', 'email', 'avatar', 'created_at')
                ->where([
                    ['name', 'like', '%' . $name . '%'],
                    ['email', 'like', '%' . $email . '%']
                ])
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->paginate($pageSize);

            foreach ($userList as $item) {
                $item->name = $item->name ?: '无名氏';
                $item->avatar = $item->avatar ? config('app.url') . $item->avatar : config('app.url') . "/default_picture.jpg";
            }

            return statusJson(200, true, 'success', json_decode(json_encode($userList), true));
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }

    /**
     * 保存用户
     * $data['id'] 存在更新 不存在新增
     * @param Request $request
     * @return false|string
     */
    public function userSave(Request $request): false|string
    {
        $data = $request->all();

        try {
            if (!isset($data['id'])) {
                $validator = Validator::make(
                    $data,
                    [
                        'name' => 'required|string|min:2|max:18',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|min:6|max:18',
                        'confirmPassword' => 'required|same:password',
                    ],
                    [
                        'name.required' => '用户名不能为空',
                        'name.min' => '用户名不能少于2个字符',
                        'name.max' => '用户名不能超过18个字符',
                        'email.required' => '邮箱不能为空',
                        'email.email' => '邮箱格式不正确',
                        'email.unique' => '邮箱已被注册',
                        'password.required' => '密码不能为空',
                        'password.min' => '密码不能少于6位',
                        'password.max' => '密码不能超过18位',
                        'confirmPassword.required' => '确认密码不能为空',
                        'confirmPassword.same' => '确认密码与密码不一致',
                    ]
                );

                if ($validator->fails()) {
                    return statusJson(400, false, $validator->errors()->first());
                }

                // 验证通过
                $users = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password'])
                ]);

                // 分配权限
                Authority::create([
                    'email' => $users->email,
                    'name' => $users->name,
                    'user_id' => $users->id,
                    'menu_id' => $data['menuId'],
                ]);
            } else {
                $validator = Validator::make(
                    $data,
                    [
                        'name' => 'required|string|min:2|max:18',
                    ],
                    [
                        'name.required' => '用户名不能为空',
                        'name.min' => '用户名不能少于2个字符',
                        'name.max' => '用户名不能超过18个字符'
                    ]
                );

                User::where('id', $data['id'])
                    ->update([
                        'name' => $data['name']
                    ]);

                Authority::where('user_id', $data['id'])
                    ->update([
                        'menu_id' => $data['menuId']
                    ]);

                if ($validator->fails()) {
                    return statusJson(400, false, $validator->errors()->first());
                }
            }
            return statusJson(200, true, 'success');
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }

    /**
     * 删除用户 -- 软删除
     * @param Request $request
     * @return false|string
     */
    public function deleteUser(Request $request): false|string
    {
        $id = $request->input('id');

        try {
            User::where('id', $id)
                ->delete();

            $data = User::withTrashed()
                ->select('id as item_id', 'name')
                ->where('id', $id)
                ->get()
                ->toArray();

            // 获取到表名
            $tableName = (new User())->getTable();

            foreach ($data as $key => $item) {
                $data[$key]['label'] = $tableName;
                $data[$key]['type'] = '用户';
                $data[$key]['created_at'] = date('Y-m-d H:i:s', time());
                $data[$key]['updated_at'] = date('Y-m-d H:i:s', time());

                Recycle::insert($data[$key]);
            }

            return statusJson(200, true, 'success');
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }
}
