<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineRichMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_rich_menus', function (Blueprint $table) {
            $table->string('id')->comment('リッチメニューID')->unique();
            $table->string('vendor_id')->comment('業者ID');
            $table->string('user_id', 64)->comment('ラインID');
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
        Schema::dropIfExists('line_rich_menus');
    }
}
