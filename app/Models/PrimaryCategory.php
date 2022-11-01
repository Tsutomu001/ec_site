<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// SecondaryCategoryの定義
use App\Models\SecondaryCategory;

class PrimaryCategory extends Model
{
    use HasFactory;

    public function secondary()
    {
        // PrimaryCategory(親)とSecondaryCategory(子)のリレーション...1対多
        return $this->hasMany(SecondaryCategory::class);
    }
}
