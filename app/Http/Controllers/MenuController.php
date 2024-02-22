<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * 获取菜单
     * $type == 'aside' 获取用户权限对应菜单 左侧菜单列表
     * $type == 'all'  获取所有菜单
     * @param Request $request
     * @return false|string
     */
    public function getMenuList(Request $request): false|string
    {
        $id = $request->header('user_id');
        $type = $request->input('type');
        $res = [];

        if ($type == 'aside') { //  获取用户权限对应菜单 左侧菜单列表
            $user = User::find($id);

            //  获取用户权限对应菜单id
            $menuIds = $user->authority
                ->pluck('menu_id')
                ->first();
            // 将$menuIds 转数组
            $menuIds = explode(',', $menuIds);

            //   获取菜单列表
            $menus = Menu::whereIn('id', $menuIds)
                ->select('id', 'name', 'url', 'icon', 'parent_id', 'order')
                ->get()
                ->toArray();

            $res = buildListTree($menus);
        } elseif ($type == 'all') { //  获取所有菜单列表
            // 分页查询 每页20条
            $res = Menu::paginate(20)->toArray();

            $res['data'] = buildListTree($res['data']);
        }

        return statusJson(200, true, 'success', $res);
    }
}