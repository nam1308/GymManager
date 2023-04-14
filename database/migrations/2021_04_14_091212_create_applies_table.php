<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applies', function (Blueprint $table) {
            $table->string('id')->comment('ベンダーID')->unique();
            $table->string('company_name')->comment('会社名・屋号');
            $table->string('postal_code')->comment('郵便番号');
            $table->string('prefecture_id', 100)->comment('都道府県ID');
            $table->string('municipality')->comment('市区町村');
            $table->string('address_building_name')->comment('番地・ビル名');
            $table->string('phone_number')->comment('電話番号');
            $table->string('name')->comment('お名前');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->string('password')->comment('パスワード');
            $table->string('status')->nullable()->comment('ステータス');
            $table->dateTime('status_updated_at')->nullable()->comment('ステータスを更新した日');
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
        Schema::dropIfExists('applies');
    }
}
