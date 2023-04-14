<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_infos', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->integer('purchase_id')->comment('購入ID')->index();
            $table->integer('user_id')->comment('購入者ID')->index();
            $table->string('product_code')->comment('製品コード')->index();
            $table->string('introducer')->nullable(true)->comment('紹介者');
            $table->string('question_1')->nullable(true)->comment('本サービスをどこでお知りになりましたか？');
            $table->string('question_2')->nullable(true)->comment('何に興味がありますか？');
            $table->string('other')->nullable(true)->comment('その他');
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
        Schema::dropIfExists('purchase_infos');
    }
}
