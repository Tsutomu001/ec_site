<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル定義
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;
// DBファサードを使用するため定義する
use Illuminate\Support\Facades\DB;
// Mailファサードを使用するために定義する
use Illuminate\Support\Facades\Mail;
// Mailクラスを定義する
use App\Mail\TestMail;
// SendThanksMailを定義する
use App\Jobs\SendThanksMail; 

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');

        // 単一コントローラでmiddlewareを定義する
        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('item'); //itemのid取得(文字列) 
            if(!is_null($id)){ // null判定 
                    // Product::availableItems() ...is_sellingがtrueの場合のみ取得する
                    $itemId = Product::availableItems()->where('products.id' , $id)->exists();
                if(!$itemId){ // 同じでなかったら 
                    abort(404); // 404画面表示 
                    } 
            } 
            return $next($request); 
        });
    }

    public function index(Request $request)
    {
        // 同期的に送信
        // テストメール送信のコード
        // Mail::to('test@example.com')
        // ->send(new TestMail());

        // 非同期的に送信
        SendThanksMail::dispatch();


        // secondaryはModelsのSecondaryCategoryのpublic functionで定義したもの
        $categories = PrimaryCategory::with('secondary')
        ->get();

        // Models(Product.php)の処理内容
        $products = Product::availableItems()
        ->selectCategory($request->category ?? '0')
        ->searchKeyword($request->keyword)
        ->get();

        return view('user\auth.index', compact('products','categories'));
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
