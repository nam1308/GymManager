<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->string('name')->comment('メニュー名')->nullable();
            $table->integer('price')->comment('１回価格')->nullable();
            $table->integer('time')->comment('予約合計時間')->nullable();
            $table->integer('course_time')->comment('時間')->nullable();
            $table->integer('course_minutes')->comment('分')->nullable();
            $table->text('contents')->comment('メニュー説明')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
