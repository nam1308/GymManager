<?php

use Database\Seeders\ProductCategoriesSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                //AdminTableSeeder::class,
                //UserTableSeeder::class,
                //BasicSettingSeeder::class,
                SuperAdminTableSeeder::class,
                ProductCategoriesSeeder::class,
            ]
        );
    }
}
