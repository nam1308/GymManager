<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->string('sei')->comment('姓');
            $table->string('mei')->comment('名');
            $table->string('sei_kana')->comment('セイ');
            $table->string('mei_kana')->comment('メイ');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->date('birthday')->comment('誕生日');
            $table->string('gender_id', 1)->default('1')->comment('性別ID');
            $table->string('phone_number')->comment('電話番号');
            $table->text('self_introduction')->comment('自己紹介');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('trainers');
    }
}
