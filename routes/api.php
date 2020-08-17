<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route pour User => creation / login
    Route::post("create-user", "UserController@createUser");
    Route::post("user-login", "UserController@userLogin");
    Route::delete("user-delete/{user}", "UserController@delete");
    

Route::group(['middleware' => 'auth:api'], function () {
    Route::get("user-detail", "UserController@userDetail");
    Route::get("user-all","UserController@index");
    Route::post('user-logout','UserController@logout');
    Route::post('user-update','UserController@update'); 
    Route::post('user-update-admin','UserController@updateadmin');  
});


//Route pour product => create/ delete /update / search
Route::get("product-detail/{slug}", "ProductController@productDetail");
Route::get("product-listing/{q?}", "ProductController@productListing");
Route::get("product-search/{q}", "ProductController@search");

Route::group(['middleware' => 'auth:api'], function () {
    Route::post("create-product", "ProductController@createProduct");
    Route::post("update-product", "ProductController@updateProduct");
    Route::get("delete-product/{id}", "ProductController@destroy");
    Route::get("product-listing-user", "ProductController@productUser");
    Route::post("chat-message", "ChatController@create");
    Route::get("chat-all","ChatController@index");
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


