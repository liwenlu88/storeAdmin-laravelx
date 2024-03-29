<?php

namespace Database\Seeders;

use App\Models\Authority;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuthoritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auth = Authority::factory(2)->create();

        $AuthArray = [
            [
                'email' => '2816842036@qq.com',
                'name' => 'admin',
                'user_id' => 1
            ],
            [
                'email' => 'testadmin@qq.com',
                'name' => '测试用户',
                'user_id' => 2,
                'menu_id' => json_decode(json_encode([
                    1, 2, 3, 4, 5
                ]))
            ]
        ];

        foreach ($AuthArray as $key => $item) {
            $auth[$key]->update($item);
        }
    }
}