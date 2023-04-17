<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->integer('login_id')->unique()->comment('ログインID');
            $table->string('name')->comment('お名前');
            $table->string('email')->comment('メールアドレス');
            $table->string('password')->comment('パスワード');
            $table->tinyInteger('role')->default(0)->index('index_role')->comment('ロール');
            $table->tinyInteger('trainer_role')->nullable()->comment('トレーナー');
            $table->boolean('block')->default(false);
            $table->text('self_introduction')->comment('自己紹介')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();

            //プライマリーキー設定
            $table->unique(['vendor_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
