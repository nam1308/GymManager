<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_infos', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->string('callback')->comment('コールバック');
            $table->string('login_channel_id')->comment('ログインチャンネルID');
            $table->string('login_channel_secret')->comment('ログインチャネルシークレット');
            $table->string('message_channel_id')->comment('メッセージチャンネルID');
            $table->string('message_channel_secret')->comment('メッセージチャネルシークレット');
            $table->string('message_channel_access_token')->comment('メッセージチャネルアクセストークン');
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
        Schema::dropIfExists('line_infos');
    }
}
