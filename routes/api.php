<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ClassificacoesController;

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

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::get('/classificacao/getall', [ClassificacoesController::class,'getall']); 
Route::get('/articles', [ArticleController::class,'index']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/teste', function () {
        return 'Hello World';
    });

    Route::post('/logout', [AuthController::class,'logout']);


    //Lista de Artigos
    //Route::get('/articles', [ArticleController::class,'index']);
    Route::get('articles/{id}', 'ArticleController@show');
    Route::post('articles', 'ArticleController@store');
    Route::delete('articles/{id}', 'ArticleController@destroy');

});




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
