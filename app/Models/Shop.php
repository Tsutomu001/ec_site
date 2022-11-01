<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Ownerモデルを使用するため
use App\Models\Owner;
// Productモデルを使用するため
use App\Models\Product;

class Shop extends Model
{
    use HasFactory;

    // DBから取得するカラムを設定する
    protected $fillable = [
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling'
    ];

    public function owner()
    {
        // Owner(親)とShop(子)のリレーション
        return $this->belongsTo(Owner::class);
    }

    public function product()
    {
        // Shop(親)とProduct(子)のリレーション...1対多
        return $this->hasmany(Product::class);
    }
}
