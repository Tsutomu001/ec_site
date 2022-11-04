<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Image定義
use App\Models\Image;
// Product定義
use App\Models\Product;
// Auth定義
use Illuminate\Support\Facades\Auth;
// UploadImageRequestの定義
use App\Http\Requests\UploadImageRequest;
// ImageServiceの定義
use App\Services\ImageService;
// Storageの定義
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        // 単一コントローラでmiddlewareを定義する
        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('image'); //imageのid取得(文字列) 
            if(!is_null($id)){ // null判定 
                    $imagesOwnerId = Image::findOrFail($id)->owner->id; //imageのidからownerのidを取得する
                    $ImageId = (int)$imagesOwnerId; // キャスト ...(int)文字列→数値に型変換 
                    // $ownerId = Auth::id();// ログインしたownerのidを取得する
                if($ImageId !== Auth::id()){ // 同じでなかったら 
                    abort(404); // 404画面表示 
                    } 
            } 
            return $next($request); 
        });
    }
    public function index()
    {
        // $ownerId = Auth::id();
        $images = Image::where('owner_id' , Auth::id())
        ->orderBy('updated_at' , 'desc')// orderBy() ...降順 (小さくなる) 
        ->paginate(20);

        return view('owner\auth.images.index',compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner\auth.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        // 複数枚の画像を取得する
        $imageFiles = $request->file('files');

        // $imageFilesが空でなかったら...
        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                // ImageServiceでアップロード処理した画像を取得する
                $fileNameToStore = ImageService::upload($imageFile,'products');
                // 保存処理を行う
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
        }

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像登録を実施しました。',
        'status' => 'info']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('owner\auth.images.edit', compact('image'));
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
        // Imagesのvalidationルール
        $request->validate([
            'title' => ['string', 'max:50']
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像情報を更新しました。',
        'status' => 'info']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Imageのidを取得
        $image = Image::findOrFail($id);

        $imageInProducts = Product::where('image1', $image->id)
        ->orWhere('image2', $image->id)
        ->orWhere('image3', $image->id)
        ->orWhere('image4', $image->id)
        ->get();

        if($imageInProducts){
            // eachメソッド ...コレクションのアイテムを繰り返しで処理し、コールバックに各アイテムを渡す
            $imageInProducts->each(function($product) use($image){
                if($product->image1 === $image->id){
                    // 使っていたらnullにする
                    $product->image1 = null;
                    $product->save();
                }
                if($product->image2 === $image->id){
                    // 使っていたらnullにする
                    $product->image2 = null;
                    $product->save();
                }
                if($product->image3 === $image->id){
                    // 使っていたらnullにする
                    $product->image3 = null;
                    $product->save();
                }
                if($product->image4 === $image->id){
                    // 使っていたらnullにする
                    $product->image4 = null;
                    $product->save();
                }
            });
        }

        // Imageを'public/products/'に保存する
        $filePath = 'public/products/' . $image->filename;

        // exists() ...存在するかどうか
        if(Storage::exists($filePath)){
            // 存在したら削除する
            Storage::delete($filePath);
        }

        // 取得したImageの画像IDも削除する
        Image::findOrFail($id)->delete();

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像を削除しました。',
        'status' => 'alert']);
    }
}
