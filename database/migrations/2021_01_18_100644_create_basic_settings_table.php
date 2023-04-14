<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasicSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_settings', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->unique();
            $table->string('company_name', 255)->nullable(true)->comment('会社名');
            $table->string('store_name', 255)->nullable(true)->comment('店名');
            $table->string('postal_code', 15)->nullable(true)->comment('郵便番号');
            $table->string('prefecture_id', 2)->nullable(true)->comment('都道府県ID');
            $table->string('municipality', 255)->nullable(true)->comment('市区町村');
            $table->string('business_hours', 255)->nullable(true)->comment('番地・ビル名');
            $table->string('address_building_name', 255)->nullable(true)->comment('番地・ビル名');
            $table->string('phone_number')->comment('電話番号');
            $table->text('other_memo')->comment('その他のメモ')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basic_settings');
    }
}
