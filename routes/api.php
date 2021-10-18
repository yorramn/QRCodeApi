<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QRCodeController;
use App\Http\Controllers\Api\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
    Route::group(['prefix' => 'qrcodes'], function(){
        Route::get('', [QRCodeController::class, 'index'])->name('qrcodes.index');
        Route::post('', [QRCodeController::class, 'store'])->name('qrcodes.store');
    });

    // logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
