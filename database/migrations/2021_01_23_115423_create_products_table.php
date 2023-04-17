<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->comment('業者ID')->index();
            $table->string('name', 255)->nullable(true)->comment('商品名');
            $table->string('code', 255)->nullable(true)->unique(true)->comment('商品コード');
            $table->string('client_ip', 10)->nullable(true)->comment('クライアントIP');
            $table->integer('product_category_id')->nullable(true)->comment('商品カテゴリー');
            $table->integer('initial_price')->default(0)->nullable(true)->comment('価格');
            $table->integer('purchase_count')->nullable(true)->comment('購入数（現在）');
            $table->integer('free_term')->nullable(true)->comment('無料期間');
            $table->dateTime('reference_date')->nullable();
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
        Schema::dropIfExists('products');
    }
}
