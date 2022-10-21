<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

// Routeファサードを使用するため定義する
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    // 変数をそれぞれのログイン画面として定義する
    protected $user_route = 'user.login';
    protected $owner_route = 'owner.login';
    protected $admin_route = 'admin.login';
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // expectsJsonメソッド...、受信リクエストがJSONリクエストを期待しているかを判定する
        // つまり以下の処理は「ログインユーザーではなかったら....」という意味
        if (! $request->expectsJson()) {
            // isメゾット...○○○関連のルート
            if(Route::is('owner.*')){
                return route($this->owner_route);
            } elseif(Route::is('admin.*')){
                return route($this->admin_route);
            } else {
                return route($this->user_route);
            }
        }
    }
}
