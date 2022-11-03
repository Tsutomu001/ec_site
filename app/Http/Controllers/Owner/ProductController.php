<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Auth定義
use Illuminate\Support\Facades\Auth;
// DBファサードの定義
use Illuminate\Support\Facades\DB;
// Image定義
use App\Models\Image;
// Shop定義
use App\Models\Shop;
// Product定義
use App\Models\Product;
// PrimaryCategory定義
use App\Models\PrimaryCategory;
// Owner定義
use App\Models\Owner;
// Stock定義
use App\Models\Stock;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        // 単一コントローラでmiddlewareを定義する
        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('product'); //productのid取得(文字列) 
            if(!is_null($id)){ // null判定 
                    $productsOwnerId = Product::findOrFail($id)->shop->owner->id; //productのidからownerのidを取得する
                    $productId = (int)$productsOwnerId; // キャスト ...(int)文字列→数値に型変換 
                if($productId !== Auth::id()){ // 同じでなかったら 
                    abort(404); // 404画面表示 
                    } 
            } 
            return $next($request); 
        });
    }

    public function index()
    {
        // ログインしているownerのpuroductを取得する
        // $products = Owner::findOrFail(Auth::id())->shop->product;

        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id',Auth::id())->get();

        // dd($ownerInfo);

        // foreach($ownerInfo as $owner){
        //     // dd($owner->shop->product);
        //     foreach($owner->shop->product as $product){
        //         dd($product->imageFirst->filename);
        //     }
        // }

        return view('owner\auth.products.index',
        compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::where('owner_id',Auth::id())
        ->select('id','name')
        ->get();

        $images = Image::where('owner_id', Auth::id())
        ->select('id','title','filename')
        ->orderBy('updated_at','desc')
        ->get();

        // secondaryはModelsのSecondaryCategoryのpublic functionで定義したもの
        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner\auth.products.create',
                compact('shops','images','categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        // validation
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'information' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'integer'],
            'sort_order' => ['nullable', 'integer'],
            'quantity' => ['required', 'integer'],
            'shop_id' => ['required', 'exists:shops,id'],// exists:...存在するかどうか？
            'category' => ['required', 'exists:secondary_categories,id'],
            'image1' => ['nullable' , 'exists:images,id'],
            'image2' => ['nullable' , 'exists:images,id'],
            'image3' => ['nullable' , 'exists:images,id'],
            'image4' => ['nullable' , 'exists:images,id'],
            'is_selling' => ['required']
        ]);

        // トランザクション処理
        // Throwable ...例外を取得する
        try{
            // クロージャーの中で$requestを使用するため定義する。
            DB::transaction(function () use($request) {
                // 保存処理
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling,
            ]);

            // 紐付いたデータ
            Stock::create([
                'product_id' => $product->id,
                'type' => 1,
                'quantity' => $request->quantity
            ]);

        },2);
        }catch(Throwable $e){
            // もしエラーが出たらLogを出力
            Log::error($e);
            // 画面上に出力する
            throw $e;
        }

        //routeの場合は"\auth"は、使用しない 
        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品登録しました。',
        'status' => 'info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
