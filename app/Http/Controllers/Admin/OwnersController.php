<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Eloquentの定義
use App\Models\Owner;
use App\Models\Shop;
// クエリビルダの定義
use Illuminate\Support\Facades\DB;
// Carbonの定義
use Carbon\Carbon;
// Hashの定義
use Illuminate\Support\Facades\Hash;
// Throwableの定義
use Throwable;
// Logの定義
use Illuminate\Support\Facades\Log;


class OwnersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // // Carbon
        // $data_now = Carbon::now();
        // $data_parse = Carbon::parse(now());
        // echo $data_now->year;
        // echo $data_parse;

        // // Eloquent
        // $e_all = Owner::all();
        // // クエリビルダ
        // $q_get = DB::table('owners')->select('name', 'created_at')->get();
        // $q_first = DB::table('owners')->select('name')->first();
        // // コレクション
        // $c_test = collect([
        //     'name' => 'てすと'
        // ]);
        // dd($e_all,$q_get,$q_first,$c_test);

        // return view('admin\auth.owners.index', compact('e_all', 'q_get'));

        $owners = Owner::select('id','name','email','created_at')
                ->paginate(3);

        return view('admin\auth.owners.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin\auth.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // RegisteredUserControllerから引用
        // validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // トランザクション処理
        // Throwable ...例外を取得する
        try{
            // クロージャーの中で$requestを使用するため定義する。
            DB::transaction(function () use($request) {
                // 保存処理
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
            ]);

            Shop::create([
                'owner_id' => $owner->id,
                'name' => '店名を入力してください',
                'information' => '',
                'filename' => '',
                'is_selling' => true
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
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー登録を実施しました。。',
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
        // find() ...idが見つからなかったらエラー文
        // findOrfail() ...idが見つからなかったら404 Not Found
        $owner = Owner::findOrFail($id);
        // dd($owner);

        return view('admin\auth.owners.edit', compact('owner'));
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
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー情報を更新しました。',
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
        Owner::findOrFail($id)->delete();

        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー情報を削除しました。',
        'status' => 'alert']);
    }

    public function expiredOwnerIndex(){ 
        //onlyTrashed() ...ソフトデリートされたモデルのみ取得する
        $expiredOwners = Owner::onlyTrashed()->paginate(3);
        return view('admin\auth.expired-owners', compact('expiredOwners')); 
    } 
    
    public function expiredOwnerDestroy($id){ 
        Owner::onlyTrashed()->findOrFail($id)->forceDelete(); 
        return redirect()->route('admin.expiredowners.index'); 
    }
}
