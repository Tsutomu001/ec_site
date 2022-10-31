<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 認証機能として使用するため
use Illuminate\Foundation\Auth\User as Authenticatable;

// ソフトデリートを使用するため
use Illuminate\Database\Eloquent\SoftDeletes;

// Shopモデルを使用するため
use App\Models\Shops;

// Imageモデルを使用するため
use App\Models\Image;

class Owner extends Authenticatable
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shop()
    {
        // Owner(親)とShop(子)のリレーション...1対1
        return $this->hasOne(Shop::class);
    }

    public function image()
    {
        // Owner(親)とimage(子)のリレーション...1対多
        return $this->hasMany(Image::class);
    }
}
