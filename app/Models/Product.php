<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Shopモデルを使用するため
use App\Models\Shop;
// SecondaryCategoryモデルを使用するため
use App\Models\SecondaryCategory;
// Imageモデルを使用するため
use App\Models\Image;
// Stockモデルを使用するため
use App\Models\Stock;
// Userモデルを使用するため
use App\Models\User;

class Product extends Model
{
    use HasFactory;

    // DBから取得するカラムを設定する
    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

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

    public function imageSecond()
    {
        // Image(親)とProduct(子)のリレーション...1対多
        // (Image::class, '紐づく「親」のカラム', '紐づく「子」のカラム')
        return $this->belongsTo(Image::class, 'image2', 'id');
    }

    public function imageThird()
    {
        // Image(親)とProduct(子)のリレーション...1対多
        // (Image::class, '紐づく「親」のカラム', '紐づく「子」のカラム')
        return $this->belongsTo(Image::class, 'image3', 'id');
    }

    public function imageFourth()
    {
        // Image(親)とProduct(子)のリレーション...1対多
        // (Image::class, '紐づく「親」のカラム', '紐づく「子」のカラム')
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    public function stock()
    {
        // Product(親)とStock(子)のリレーション...1対多
        return $this->hasmany(Stock::class);
    }

    // 多対多のリレーション(Users)
    public function users() 
    { 
        // 第2引数で中間テーブル名
        return $this->belongsToMany(User::class, 'carts') 
        // withPivot() ...中間テーブルのカラム取得
        ->withPivot(['id', 'quantity']); 
    }
}
