<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineTextMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_text_messages', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID');
            $table->string('user_id', 64)->comment('ラインID');
            $table->string('message')->comment('メッセージ');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['vendor_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_text_messages');
    }
}
