<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 外部キーはSeederDataと同じ数にする
            'product_id' => Product::factory(), 
            'type' => $this->faker->numberBetween(1,2), 
            'quantity' => $this->faker->randomNumber,
        ];
    }
}
