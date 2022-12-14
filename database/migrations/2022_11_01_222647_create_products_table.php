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
            $table->string('name');
            $table->text('information');
            $table->unsignedInteger('price');
            $table->boolean('is_selling');
            $table->integer('sort_order')->nullable();

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
            $table->foreignId('image2')
            ->nullable()
            ->constrained('images');
            $table->foreignId('image3')
            ->nullable()
            ->constrained('images');
            $table->foreignId('image4')
            ->nullable()
            ->constrained('images');
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
