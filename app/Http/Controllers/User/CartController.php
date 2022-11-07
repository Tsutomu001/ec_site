<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Auth定義
use Illuminate\Support\Facades\Auth;
// Cartモデル定義
use App\Models\Cart;

class CartController extends Controller
{
    public function add(Request $request)
    {
        // 'product_id'と'user_id'が正しいかどうか？
        $itemInCart = Cart::where('product_id' , $request->product_id)
            ->where('user_id' , Auth::id())->first();

        if($itemInCart){
            // カートに入れた数を加算していく
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();

        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }
        dd('テスト');
    }
}
