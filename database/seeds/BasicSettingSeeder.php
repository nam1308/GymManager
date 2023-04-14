<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('basic_settings')->insert([
            'vendor_id' => 1,
            'company_name' => '',
            'company_name_kana' => 'カブシキガイシャメデュ',
            'postal_code' => '6640858',
            'prefecture_id' => '28',
            'municipality' => '伊丹市西台１－２－３',
            'address_building_name' => '山本不動産ビル３０２',
            'phone_number_1' => '072',
            'phone_number_2' => '744',
            'phone_number_3' => '1187',
            'business_hours' => '平日 9:00-14:00',
        ]);
    }
}
