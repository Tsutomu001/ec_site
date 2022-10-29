<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// modelの定義
use App\Models\Shop;
// Authの定義
use Illuminate\Support\Facades\Auth;
// Storageの定義
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        // 単一コントローラでmiddlewareを定義する
        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('shop'); //shopのid取得(文字列) 
            if(!is_null($id)){ // null判定 
                    $shopsOwnerId = Shop::findOrFail($id)->owner->id; //shopのidからownerのidを取得する
                    $shopId = (int)$shopsOwnerId; // キャスト ...(int)文字列→数値に型変換 
                    $ownerId = Auth::id();// ログインしたownerのidを取得する
                if($shopId !== $ownerId){ // 同じでなかったら 
                    abort(404); // 404画面表示 
                    } 
            } 
            return $next($request); 
        });
    }

    public function index()
    {
        // $ownerId = Auth::id();
        $shops = Shop::where('owner_id' , Auth::id())->get();

        return view('owner\auth.shops.index',compact('shops'));
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        // dd(Shop::findOrFail($id));
        return view('owner\auth.shops.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $imageFile = $request->image; //「$imageFile」に一時保存する 

        // isValid() ...ファイルが存在するかどうかを判定することに加え、ファイルのアップロードに問題がなかったことを確認する
        if(!is_null($imageFile) && $imageFile->isValid() ){ 
        // putFile() ...もしフォルダがなかったらshopsフォルダを作成し、画像を入れる
        Storage::putFile('public/shops', $imageFile); 
        } 

        return redirect()->route('owner.shops.index');
    }
}
