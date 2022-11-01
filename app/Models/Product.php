<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Shopモデルを使用するため
use App\Models\Shop;
// Shopモデルを使用するため
use App\Models\SecondaryCategory;
// Shopモデルを使用するため
use App\Models\Image;

class Product extends Model
{
    use HasFactory;

    public function shop()
    {
        // Shop(親)とProduct(子)のリレーション...1対多
        return $this->belongsTo(shop::class);
    }

    // public functionがリレーション先のモデル名でない場合

    public function category()
    {
        // SecondaryCategory(親)とProduct(子)のリレーション...1対多
        // SecondaryCategory(親)の'secondary_category_id'と紐付ける
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    public function imageFirst()
    {
        // Image(親)とProduct(子)のリレーション...1対多
        // (Image::class, '紐づく「親」のカラム', '紐づく「子」のカラム')
        return $this->belongsTo(Image::class, 'image1', 'id');
    }
}
