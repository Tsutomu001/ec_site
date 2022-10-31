<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // foreignIdメソッド ...UNSIGNED BIGINTカラムを作成する
            // constrainedメソッド ...規約を使用して参照されるテーブルとカラムの名前を決定する
            $table->foreignId('owner_id')
            ->constrained()
            // 親モデル(owner)と連動させるために定義する
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('name'); 
            $table->text('information'); 
            $table->string('filename');
            // 販売中or販売中止(0か1で返ってくる)
            $table->boolean('is_selling');

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
        Schema::dropIfExists('shops');
    }
}
