<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// モデル定義
use App\Models\Product;

class ItemController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('user\auth.index', compact('products'));
    }
}
