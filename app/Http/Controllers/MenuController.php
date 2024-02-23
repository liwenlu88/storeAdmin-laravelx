<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * 获取菜单
     * $type == 'Aside' 获取用户权限对应菜单 左侧菜单列表
     * $type == 'All'  获取所有菜单
     * @param Request $request
     * @param int $pageSize
     * @return false|string
     */
    public function getMenuList(Request $request, int $pageSize = 0): false|string
    {
        $id = $request->header('user_id');
        $type = $request->input('type');
        $res = [];

        if ($type == 'Aside') { //  获取用户权限对应菜单 左侧菜单列表
            $user = User::find($id);

            //  获取用户权限对应菜单id
            $menuIds = $user->authority
                ->pluck('menu_id')
                ->first();
            // 将$menuIds两侧的"[]"去除 转成数组
            $menuIds = explode(',', str_replace(['[', ']', '"'], '', $menuIds));

            //   获取菜单列表
            $menus = Menu::whereIn('id', $menuIds)
                ->select('id', 'name', 'url', 'icon', 'level', 'parent_id', 'order')
                ->get()
                ->toArray();

            $res = buildListTree($menus);
        } elseif ($type == 'All') { //  获取所有菜单列表
            $pageSize = $request->input('page_size', $pageSize);
            // 分页查询 每页20条
            $res = Menu::where('level', 1)
                ->with('children')
                ->paginate($pageSize)->toArray();

            $res['data'] = buildListTree($res['data']);
        }

        return statusJson(200, true, 'success', $res);
    }

    public function deleteMenu(Request $request): false|string
    {
        $id = $request->input('id');
        try {
            Menu::where('id', $id)
                ->whereOr('parent_id', $id)
                ->delete();
            return statusJson(200, true, '删除成功');
        } catch (Exception $e) {
            return statusJson(400, false, '删除失败', json_decode($e->getMessage()));
        }
    }
}