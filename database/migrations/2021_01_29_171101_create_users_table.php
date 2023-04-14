<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 64)->primary()->comment('ラインID')->nullable();
            $table->string('display_name')->comment('ラインの表示名')->nullable();
            $table->string('name')->comment('名前')->nullable();
            $table->string('name_kana')->comment('名前（カタナカ）')->nullable();
            $table->string('picture_url')->comment('ラインプロフィール画像')->nullable();
            $table->string('phone_number')->comment('電話番号')->nullable();
            $table->string('email')->unique()->comment('メールアドレス')->nullable();
            $table->timestamp('email_verified_at')->nullable()->nullable();
            $table->string('password')->comment('パスワード')->nullable();
            $table->date('birthday')->comment('誕生日')->nullable();
            $table->string('birthday_search')->comment('誕生日')->nullable();
            $table->string('gender_id', 1)->comment('性別ID')->nullable();
            $table->timestamps();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
