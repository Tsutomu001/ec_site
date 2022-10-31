<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Image定義
use App\Models\Image;
// Auth定義
use Illuminate\Support\Facades\Auth;
// UploadImageRequestの定義
use App\Http\Requests\UploadImageRequest;
// ImageServiceの定義
use App\Services\ImageService;

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
                if($imageId !== Auth::id()){ // 同じでなかったら 
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
