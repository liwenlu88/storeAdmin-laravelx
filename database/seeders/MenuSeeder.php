<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = Menu::factory(9)->create();

        $menuArray = [
            [
                'name' => 'Dashboard',
                'url' => '/',
                'icon' => 'home-filled',
                'parent_id' => 0,
                'order' => 1,
                'is_deleted' => 0
            ],
            [
                'name' => '店铺管理',
                'url' => '/shop/list',
                'icon' => 'shop',
                'parent_id' => 0,
                'order' => 2,
                'is_deleted' => 0
            ],
            [
                'name' => '商品管理',
                'url' => '/commodity/list',
                'icon' => 'goods',
                'parent_id' => 0,
                'order' => 3,
                'is_deleted' => 0
            ],
            [
                'name' => '广告管理',
                'url' => '/advertise/list',
                'icon' => 'document',
                'parent_id' => 0,
                'order' => 4,
                'is_deleted' => 0
            ],
            [
                'name' => '用户管理',
                'url' => '/users/list',
                'icon' => 'user',
                'parent_id' => 0,
                'order' => 5,
                'is_deleted' => 0
            ],
            [
                'name' => '系统设置',
                'url' => 'system',
                'icon' => 'lock',
                'parent_id' => 0,
                'order' => 6,
                'is_deleted' => 0
            ],
            [
                'name' => '设置',
                'url' => '/system/setting',
                'icon' => 'setting',
                'parent_id' => 6,
                'order' => 1,
                'is_deleted' => 0
            ],
            [
                'name' => '菜单',
                'url' => '/system/menus',
                'icon' => 'menu',
                'parent_id' => 6,
                'order' => 2,
                'is_deleted' => 0
            ],
            [
                'name' => '角色',
                'url' => '/system/roles',
                'icon' => 'avatar',
                'parent_id' => 6,
                'order' => 3,
                'is_deleted' => 0
            ]
        ];

        foreach ($menuArray as $key => $item) {
            $menus[$key]->update($item);
        }
    }
}