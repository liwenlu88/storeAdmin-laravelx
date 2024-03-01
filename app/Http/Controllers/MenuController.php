<?php

namespace App\Http\Controllers;

use App\Models\Authority;
use App\Models\Menu;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($type == 'Aside') { // 获取用户权限对应菜单 左侧菜单列表
            $user = User::find($id);

            // 获取用户权限对应菜单id
            $menuIds = $user->authority
                ->pluck('menu_id')
                ->first();

            // 获取菜单列表
            $menus = Menu::whereIn('id', $menuIds)
                ->select('id', 'name', 'url', 'icon', 'level', 'parent_id', 'order')
                ->where([
                    'is_visible' => 1,
                    'is_deleted' => 0
                ])
                ->get()
                ->toArray();

            $res = buildListTree($menus);
        } elseif ($type == 'All') { //  获取所有菜单列表
            $pageSize = $request->input('page_size', $pageSize);
            // 分页查询 每页20条
            $res = Menu::where('level', 0)
                ->with('children')
                ->paginate($pageSize)
                ->toArray();

            $res['data'] = buildListTree($res['data']);
        }

        return statusJson(200, true, 'success', $res);
    }

    /**
     * 获取菜单详情
     * @param Request $request
     * @return false|string
     */
    public function menuDetail(Request $request): false|string
    {
        $id = $request->input('id');
        $menu = Menu::find($id)->toArray();
        return statusJson(200, true, 'success', $menu);
    }

    /**
     * 保存菜单
     * $data['id'] 存在更新 不存在新增
     * @param Request $request
     * @return false|string
     */
    public function menuSave(Request $request): false|string
    {
        $data = $request->all();

        try {
            if (!isset($data['id'])) {
                // 新增数据并获取 ID
                $menu = Menu::create($data);
                $newId = $menu->id;

                // 获取 Authority 模型并更新 menu_id 字段
                $authority = Authority::where('user_id', 1)->first();
                // 判断 $authority->menu_id 里是否存在 $newId
                if (!in_array($newId, $authority->menu_id)) {
                    // 如果 $newId 不在 $authority->menu_id 数组中，则添加
                    $authority->menu_id = array_merge($authority->menu_id, [$newId]);
                    $authority->save();
                }
            } else {
                Menu::where('id', $data['id'])->update($data);
            }

            return statusJson(200, true, 'success');
        } catch (Exception $e) {
            return statusResponse(400, false, $e->getMessage());
        }
    }

    /**
     * 删除菜单 -- 软删除
     * @param Request $request
     * @return false|string
     */
    public function deleteMenu(Request $request): false|string
    {
        $id = $request->input('id');
        try {
            Menu::where('id', $id)
                ->whereOr('parent_id', $id)
                ->update(['is_deleted' => 1]);
            return statusJson(200, true, 'success');
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }
}