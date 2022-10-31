<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            // foreignIdメソッド ...UNSIGNED BIGINTカラムを作成する
            // constrainedメソッド ...規約を使用して参照されるテーブルとカラムの名前を決定する
            $table->foreignId('owner_id')
            ->constrained()
            // 親モデルと連動させるために定義する
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('filename');
            // nullable() ...空でも登録できる
            $table->string('title')->nullable();
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
        Schema::dropIfExists('images');
    }
}
