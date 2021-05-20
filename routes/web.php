<?php

use App\Http\Controllers\AccountPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
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
    Route::get('/register', [RegisterController::class, 'registerCreate'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerStore'])->name('register');
    Route::get('/', [LoginController::class, 'loginCreate'])->name('login');
    Route::post('/', [LoginController::class, 'loginStore'])->name('login');
    Route::get('/login/second', [SecondLoginController::class, 'loginCreate'])->name('login.create');
    Route::post('/login/second', [SecondLoginController::class, 'loginStore'])->name('login.store');

    Route::get('/account',[AccountPageController::class,'accountPageShow'])
        ->name('account')
        ->middleware('auth');
    Route::get('/logout',[LogoutController::class,'logout'])
        ->name('logout')
        ->middleware('auth');
});
