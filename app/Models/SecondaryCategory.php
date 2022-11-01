<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// PrimaryCategoryの定義
use App\Models\PrimaryCategory;

class SecondaryCategory extends Model
{
    use HasFactory;

    public function primary()
    {
        // PrimaryCategory(親)とSecondaryCategory(子)のリレーション...1対多
        return $this->belongsTo(PrimaryCategory::class);
    }
}
