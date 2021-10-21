<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QRCodeController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\ChartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use LaravelQRCode\Facades\QRCode;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
Route::group(['middleware' => ['apiJwt']], function () {

    //group of qrcodes
    Route::group(['prefix' => 'qrcodes'], function () {
        Route::get('', [QRCodeController::class, 'index'])->name('qrcodes.index');
        Route::post('', [QRCodeController::class, 'store'])->name('qrcodes.store');

        Route::group(['prefix' => '{id}'], function () {
            Route::get('', [QRCodeController::class, 'show'])->name('qrcodes.show');
            Route::put('', [QRCodeController::class, 'update'])->name('qrcodes.update');
            Route::delete('', [QRCodeController::class, 'destroy'])->name('qrcodes.destroy');
        });

    });
    Route::group(['prefix' => 'charts'], function(){
        Route::get('', [ChartController::class, 'index'])->name('qrcodes.charts.index');
    });


    // logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
