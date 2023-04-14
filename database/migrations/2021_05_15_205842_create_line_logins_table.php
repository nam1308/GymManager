<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_logins', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->integer('status')->comment('ステータス');
            $table->string('callback')->comment('コールバック')->unique();
            $table->integer('channel_id')->nullable()->comment('ログインチャンネルID');
            $table->string('channel_icon')->nullable()->comment('チャンネルアイコン');
            $table->string('channel_name')->comment('チャンネル名');
            $table->string('channel_description')->nullable()->comment('チャンネル説明');
            $table->string('email')->comment('メール');
            $table->string('privacy_policy_url')->nullable()->comment('プライバシーポリシーURL');
            $table->string('terms_of_use_url')->nullable()->comment('利用規約URL');
            $table->string('channel_secret')->nullable()->comment('ログインチャンネルシークレット');
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
        Schema::dropIfExists('line_logins');
    }
}
