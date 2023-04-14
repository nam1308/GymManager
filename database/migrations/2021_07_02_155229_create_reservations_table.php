<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->string('id', 64)->comment('予約ID')->unique();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->string('user_id', 64)->comment('予約者')->nullable();
            $table->string('admin_id', 64)->comment('トレーナ');
            $table->integer('status')->default(10)->comment('10=>仮予約、20=>却下,30=>確定');
            $table->integer('category')->default(10)->comment('10=>ライン、20=>WEB');
            $table->integer('course_id')->comment('メニューID')->nullable();
            $table->integer('shop_id')->comment('店舗ID')->nullable();
            $table->dateTime('reservation_start')->comment('予約スタート');
            $table->dateTime('reservation_end')->comment('予約終了');
            $table->text('note')->comment('メモ')->nullable();
            $table->softDeletes()->index();
            $table->timestamps();
            $table->index(['reservation_start', 'reservation_end']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
