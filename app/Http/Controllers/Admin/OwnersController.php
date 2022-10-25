<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Eloquentの定義
use App\Models\Owner;
// クエリビルダの定義
use Illuminate\Support\Facades\DB;
// Carbonの定義
use Carbon\Carbon;

class OwnersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Carbon
        $data_now = Carbon::now();
        $data_parse = Carbon::parse(now());
        echo $data_now->year;
        echo $data_parse;

        // Eloquent
        $e_all = Owner::all();
        // クエリビルダ
        $q_get = DB::table('owners')->select('name', 'created_at')->get();


        // $q_first = DB::table('owners')->select('name')->first();
        // // コレクション
        // $c_test = collect([
        //     'name' => 'てすと'
        // ]);
        // dd($e_all,$q_get,$q_first,$c_test);

        return view('admin\auth.owners.index', compact('e_all', 'q_get'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
