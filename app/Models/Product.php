<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Shopモデルを使用するため
use App\Models\Shop;

class Product extends Model
{
    use HasFactory;

    public function shop()
    {
        // Shop(親)とProduct(子)のリレーション...1対多
        return $this->belongsTo(shop::class);
    }
}
