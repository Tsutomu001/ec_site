<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Ownerモデルを使用するため
use App\Models\Owner;

class Shop extends Model
{
    use HasFactory;

    public function owner()
    {
        // Owner(親)とShop(子)のリレーション
        return $this->belongsTo(Owner::class);
    }
}
