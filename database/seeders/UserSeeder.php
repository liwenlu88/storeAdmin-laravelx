<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(32)->create();

        $userArray = [
            [
                'name' => '肌无力猛男',
                'email' => '2816842036@qq.com',
                'password' => bcrypt('lwl20030608')
            ],
            [
                'name' => '测试用户',
                'email' => 'testadmin@qq.com',
                'password' => bcrypt('lwl20030608')
            ]
        ];

        foreach ($userArray as $key => $item) {
            $users[$key]->update($item);
        }
    }
}