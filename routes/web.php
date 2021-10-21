<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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
    return view('welcome');
});

Route::get('login/google/', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('login/google/callback', function () {
    $user = Socialite::driver('google')->user();

    // $user->token
});

Route::get('/teste', function () {
    $imagens = array_map(function($imagem) {
        return '/img/QRCodes/' . basename($imagem);
    }, File::glob(public_path('/img/QRCodes/*.*')));


    return view('teste', [
        'imagens' => $imagens
    ]);
});
