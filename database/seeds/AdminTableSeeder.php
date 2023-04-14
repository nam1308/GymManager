<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'vendor_id' => 1,
                'name' => '千葉武士（システム管理者）',
                'email' => 'bushi.chiba@abihc.com',
                'password' => bcrypt('adminChiba123456789'),
                'role' => '1',
            ],
            [
                'vendor_id' => 1,
                'name' => '千葉武士（一般）',
                'email' => 'bushi.chiba@gmail.com',
                'password' => bcrypt('adminChiba123456789'),
                'role' => '21',
            ],
            [
                'vendor_id' => 1,
                'name' => '鹿子木健',
                'email' => 'kk@medu.biz',
                'password' => bcrypt('ePS4RGzD'),
                'role' => '11',
            ],
            [
                'vendor_id' => 1,
                'name' => '石津つね子',
                'email' => 'ishitsu@medu.biz',
                'password' => bcrypt('VUwFu3rE'),
                'role' => '11',
            ],
            [
                'vendor_id' => 1,
                'name' => '永谷好美',
                'email' => 'nagatani@medu.biz',
                'password' => bcrypt('nrPcQj7h'),
                'role' => '11',
            ],
            [
                'vendor_id' => 1,
                'name' => '梁本ひさこ',
                'email' => 'yanamoto@medu.biz',
                'password' => bcrypt('ha7E69Cj'),
                'role' => '11',
            ],
            [
                'vendor_id' => 1,
                'name' => '関口めぐみ',
                'email' => 'sekiguchi@medu.biz',
                'password' => bcrypt('2tWrPcwu'),
                'role' => '11',
            ],
            [
                'vendor_id' => 1,
                'name' => '河瀬けい',
                'email' => 'kawase@medu.biz',
                'password' => bcrypt('V7m3SvWj'),
                'role' => '11',
            ],
        ]);
    }
}
