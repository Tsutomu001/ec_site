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
            // 'shop_id'が削除されたら「削除される」
            $table->foreignId('shop_id')
            ->constrained()
            ->onUpdate('cascade')// 親モデル(owner)と連動させるために定義する
            ->onDelete('cascade');

            // 'secondary_category_id'が削除されても「残る」
            $table->foreignId('secondary_category_id')
            ->constrained();

            // 'image1'が削除されても「残る」
            $table->foreignId('image1')
            ->nullable()
            ->constrained('images');// constrainedメゾット ...どのtableデータか指定する
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
        Schema::dropIfExists('products');
    }
}
