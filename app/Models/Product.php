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
// DBファサードを使用するため定義する
use Illuminate\Support\Facades\DB;

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

    // ローカルスコープ
    public function scopeAvailableItems($query)
    {
        $stocks = DB::table('t_stocks') 
        ->select('product_id',
        DB::raw('sum(quantity) as quantity')) // raw ...SQLをそのまま書くことができる
        ->groupBy('product_id') // groupBy() ...重複を削除したカラムを取得する
        ->having('quantity', '>', 1); // having() ...where()と同じ考え方

        return $query
        ->joinSub($stocks, 'stock', function($join){ // $stocks = 'stock'
            $join->on('products.id', '=', 'stock.product_id'); 
        })
        // join()...tableを繋げる
        // join('繋げたいtable名', '繋げられるtableのカラム', '=', '繋げたいtableのカラム')
        ->join('shops', 'products.shop_id', '=', 'shops.id') 
        ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id') 
        // as ...別名と置き換える
        ->join('images as image1', 'products.image1', '=', 'image1.id') 
        ->where('shops.is_selling', true) 
        ->where('products.is_selling', true)
        // idだけにするとjoinしたtableと被るので「as」で別名に置き換える
        ->select('products.id as id', 'products.name as name', 'products.price' 
        ,'products.sort_order as sort_order' 
        ,'products.information', 'secondary_categories.name as category' 
        ,'image1.filename as filename');
    }
}
