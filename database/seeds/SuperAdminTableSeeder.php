<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('super_admins')->insert([
            [
                'name' => '千葉武士（システム管理者）',
                'email' => 'bushi.chiba@abihc.com',
                'password' => bcrypt('chiba123456789'),
                'role' => '1',
            ],
//            [
//                'name' => '佐渡永寛（システム管理者）',
//                'email' => 'nagaric@gmail.com',
//                'password' => bcrypt('sado123456789'),
//                'role' => '1',
//            ],
            [
                'name' => '高川剛（システム管理者）',
                'email' => 'tkgw@tidalwave.pictures',
                'password' => bcrypt('takagawa1234'),
                'role' => '1',
            ],
            [
                'name' => 'Kenji Mazuka（システム管理者）',
                'email' => 'k.maduka@flama.co.jp',
                'password' => bcrypt('mazuka123456789'),
                'role' => '1',
            ],
        ]);
    }
}
