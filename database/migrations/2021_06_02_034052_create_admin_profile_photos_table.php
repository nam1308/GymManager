<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminProfilePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_profile_photos', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID');
            $table->bigInteger('admin_id')->comment('トレーナID');
            $table->string('file')->nullable()->comment('ファイル名');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['vendor_id', 'admin_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_profile_photos');
    }
}
