<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル定義
use App\Models\Product;
use App\Models\Stock;
// DBファサードを使用するため定義する
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    public function index()
    {
        $products = Product::availableItems()->get();

        return view('user\auth.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $quantity = Stock::where('product_id', $product->id) 
        ->sum('quantity');

        // 9より大きかったら強制的に9とする
        if($quantity > 9){ 
            $quantity = 9; 
        }

        return view('user\auth.show', compact('product','quantity'));
    }
}
