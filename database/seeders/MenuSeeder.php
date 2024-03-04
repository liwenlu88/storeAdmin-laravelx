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
        $menus = Menu::factory(10)->create();

        $menuArray = [
            [
                'name' => 'Dashboard',
                'url' => '/',
                'icon' => 'Home-filled',
                'order' => 1,
            ],
            [
                'name' => '店铺管理',
                'url' => '/shop/list',
                'icon' => 'Shop',
                'order' => 2,
            ],
            [
                'name' => '商品管理',
                'url' => '/commodity/list',
                'icon' => 'Goods',
                'order' => 3,
            ],
            [
                'name' => '广告管理',
                'url' => '/advertise/list',
                'icon' => 'Document',
                'order' => 4,
            ],
            [
                'name' => '用户管理',
                'url' => '/users/list',
                'icon' => 'User',
                'order' => 5,
            ],
            [
                'name' => '系统设置',
                'url' => 'system',
                'icon' => 'Lock',
                'order' => 6,
            ],
            [
                'name' => '设置',
                'url' => '/system/setting',
                'icon' => 'Setting',
                'level' => 1,
                'parent_id' => 6,
                'order' => 1,
            ],
            [
                'name' => '菜单',
                'url' => '/system/menus',
                'icon' => 'Menu',
                'level' => 1,
                'parent_id' => 6,
                'order' => 2,
            ],
            [
                'name' => '角色',
                'url' => '/system/roles',
                'icon' => 'Avatar',
                'level' => 1,
                'parent_id' => 6,
                'order' => 3,
            ],
            [
                'name' => '回收站',
                'url' => '/recycle/list',
                'icon' => 'Delete',
                'order' => 7
            ]
        ];

        foreach ($menuArray as $key => $item) {
            $menus[$key]->update($item);
        }
    }
}