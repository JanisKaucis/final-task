<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SecondLoginController;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['web']], function () {
    Route::get('/register', [RegisterController::class, 'registerCreate']);
    Route::post('/register', [RegisterController::class, 'registerStore']);
    Route::get('/login', [LoginController::class, 'loginCreate']);
    Route::post('/login', [LoginController::class, 'loginStore']);
    Route::get('/login/second', [SecondLoginController::class, 'loginCreate']);
    Route::get('/session/set',[LoginController::class,'createSession']);
    Route::get('/session/get',[SecondLoginController::class,'getSession']);
});
