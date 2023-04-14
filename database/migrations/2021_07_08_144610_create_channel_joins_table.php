<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelJoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_joins', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID');
            $table->string('user_id')->comment('会員ID');
            // $table->timestamp('blocked_at')->comment('ブロックした日付')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['vendor_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_joins');
    }
}
