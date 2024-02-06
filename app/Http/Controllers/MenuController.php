<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     *  获取 用户权限对应菜单列表
     * @param Request $request
     * @return false|string
     */
    public function getMenuList(Request $request): false|string
    {
        $id = $request->header('user_id');
        $user = User::find($id);

        //  获取用户权限对应菜单id
        $menuIds = $user->authority
            ->pluck('menu_id')
            ->first();

        //   获取菜单列表
        $menus = Menu::whereIn('id', $menuIds)
            ->select('id', 'name', 'url', 'icon', 'parent_id', 'order', 'is_deleted')
            ->get()
            ->toArray();

        $res = buildListTree($menus);

        return statusJson(200, true, 'success', $res);
    }
}