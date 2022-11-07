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
        $stocks = DB::table('t_stocks') 
        ->select('product_id',
        DB::raw('sum(quantity) as quantity')) // raw ...SQLをそのまま書くことができる
        ->groupBy('product_id') // groupBy() ...重複を削除したカラムを取得する
        ->having('quantity', '>', 1); // having() ...where()と同じ考え方

        $products = DB::table('products') 
        ->joinSub($stocks, 'stock', function($join){ // $stocks = 'stock'
            $join->on('products.id', '=', 'stock.product_id'); 
        })
        // join()...tableを繋げる
        // join('繋げたいtable名', '繋げられるtableのカラム', '=', '繋げたいtableのカラム')
        ->join('shops', 'products.shop_id', '=', 'shops.id') 
        ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id') 
        // as ...別名と置き換える
        ->join('images as image1', 'products.image1', '=', 'image1.id') 
        ->join('images as image2', 'products.image2', '=', 'image2.id') 
        ->join('images as image3', 'products.image3', '=', 'image3.id') 
        ->join('images as image4', 'products.image4', '=', 'image4.id')
        ->where('shops.is_selling', true) 
        ->where('products.is_selling', true)
        // idだけにするとjoinしたtableと被るので「as」で別名に置き換える
        ->select('products.id as id', 'products.name as name', 'products.price' 
        ,'products.sort_order as sort_order' 
        ,'products.information', 'secondary_categories.name as category' 
        ,'image1.filename as filename') 
        ->get();

        // dd($stocks, $products);
        // $products = Product::all();

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
