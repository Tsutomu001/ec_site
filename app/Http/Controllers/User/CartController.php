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
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        $lineItems = [];
        foreach($products as $product){
            $lineItem = [
                'name' => $product->name,
                'description' => $product->information,
                'amount' => $product->price,
                'currency' => 'jpy',
                'quantity' => $product->pivot->quantity,
            ];
            array_push($lineItems, $lineItem);
        }
        // dd($lineItems);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            // 支払いが成功したら
            'success_url' => route('user.items.index'),
            // 支払いが失敗したら
            'cancel_url' => route('user.cart.index'),
        ]);

        $piblicKey = env('STRIPE_PUBLIC_KEY');

        return view('user/auth.checkout',
            compact('session','publicKey'));
    }
}
