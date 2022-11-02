<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// DBファサードを使用するため定義する
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insertメゾット ...データを登録することができる
        DB::table('t_stocks')->insert([
            [
                'product_id' => 1,
                'type' => 1,
                'quantity' => 5,
            ],
            [
                'product_id' => 1,
                'type' => 1,
                'quantity' => -2,
            ],
        ]);
    }
}
