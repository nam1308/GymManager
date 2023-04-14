<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->bigInteger('user_id')->index('user_id')->comment('会員ID');
            $table->text('content')->comment('質問内容');
            $table->string('agent')->comment('エージェント');
            $table->string('ip', 20)->comment('IPアドレス');
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
        Schema::dropIfExists('contact_us');
    }
}
