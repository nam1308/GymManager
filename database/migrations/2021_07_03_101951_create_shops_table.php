<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->string('name')->comment('ショップ名');
            $table->string('postal_code')->comment('郵便番号');
            $table->string('prefecture_id')->comment('都道府県');
            $table->string('municipality')->comment('市区町村');
            $table->string('address_building_name')->comment('番地・ビル名')->nullable();
            $table->string('phone_number')->comment('電話番号')->nullable();
            $table->string('url')->comment('url')->nullable();
            $table->text('contents')->comment('内容')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
