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
        $users = User::factory(10)->create();

        $users[0]->name = 'Admin';
        $users[0]->email = '2816842036@qq.com';
        $users[0]->password = bcrypt('lwl20030608');
        $users[0]->save();
    }
}