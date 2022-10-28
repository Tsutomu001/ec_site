<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// modelの定義
use App\Models\Shop;
// Authの定義
use Illuminate\Support\Facades\Auth;
class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');
    }

    public function index()
    {
        $ownerId = Auth::id();
        $shops = Shop::where('owner_id' , $ownerId)->get();

        return view('owner\auth.shops.index',compact('shops'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }
}
