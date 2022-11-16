<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Auth定義
use Illuminate\Support\Facades\Auth;
// Cartモデル定義
use App\Models\Cart;
// Userモデル定義
use App\Models\User;
// Stockモデル定義
use App\Models\Stock;
// Common定義
use App\Constants\Common;
// CartService定義
use App\Services\CartService;
// SendThanksMail定義
use App\Jobs\SendThanksMail;

class CartController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        $totalPrice = 0;

        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        // dd($products,$totalPrice);

        return view('user\auth.cart',
            compact('products', 'totalPrice'));
    }

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
        
        return redirect()->route('user.cart.index');
    }

    public function delete($id)
    {
        Cart::where('product_id',$id)
            ->where('user_id',Auth::id())
            ->delete();

        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {

        // 
        $itemsInCart = Cart::where('user_id', Auth::id())->get();
        $products = CartService::getItemsInCart($itemsInCart);
        $user = User::findOrFail(Auth::id());

        SendThanksMail::dispatch($products, $user);
        dd('ユーザーメール送信テスト');
        // 


        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        $lineItems = [];
        foreach($products as $product){
            $quantity = '';
            // 現在の在庫数が分かるようにする処理
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');

            // もし"Cartの数量"が"商品の数量"より大きかったら
            if($product->pivot->quantity > $quantity){
                // Cartの一覧に戻す
                return redirect()->route('user.cart.index');    
            } else {
                // 決算処理
                $lineItem = [
                    'price_data' => [
                        'unit_amount' => $product->price,
                        'currency' => 'JPY',

                        'product_data' => [
                            'name' => $product->name,
                            'description' => $product->information,
                        ],
                    ],

                    'quantity' => $product->pivot->quantity,
                ];
                array_push($lineItems, $lineItem);
            }
        }
        // dd($lineItems);
        foreach($products as $product){
            // 紐付いたデータ
            Stock::create([
                'product_id' => $product->id,
                'type' => Common::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        // dd('test');

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            // 支払いが成功したら
            'success_url' => route('user.cart.success'),
            // 支払いが失敗したら
            'cancel_url' => route('user.cart.cancel'),
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user/auth.checkout',
            compact('session','publicKey'));
    }

    public function success()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.items.index');
    }

    public function cancel()
    {
        $user = User::findOrFail(Auth::id());

        foreach($user->products as $product){
            // 紐付いたデータ
            Stock::create([
                'product_id' => $product->id,
                'type' => Common::PRODUCT_LIST['add'],
                'quantity' => $product->pivot->quantity
            ]);
        }

        return redirect()->route('user.cart.index');
    }
}
