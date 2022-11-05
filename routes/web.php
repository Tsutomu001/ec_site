<?php

use Illuminate\Support\Facades\Route;
// ItemControllerの定義
use App\Http\Controllers\User\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('user\auth.welcome');
});

Route::middleware('auth:users')->group(function(){ 
    Route::get('/', [ItemController::class, 'Index'])->name('items.index'); 
});

// Route::get('/dashboard', function () {
//     return view('user\auth.dashboard');
// })->middleware(['auth:users'])->name('dashboard');

require __DIR__.'/auth.php';
