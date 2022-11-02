<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    //protected ...そのクラス自身と親子関係にあるクラスのみアクセスが可能
    protected $table = 't_stocks';
}
