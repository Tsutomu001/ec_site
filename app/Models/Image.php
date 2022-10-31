<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    // DBから取得するカラムを設定する
    protected $fillable = [
        'owner_id',
        'filename'
    ];
}
