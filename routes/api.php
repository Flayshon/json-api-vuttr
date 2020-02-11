<?php

use Illuminate\Http\Request;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//API Public Documentation
Route::get('/docs', function () {
    return view('docs.index');
});

//JWT Token management
Route::post('/register',            'Api\Auth\AuthController@register');
Route::post('/login',               'Api\Auth\AuthController@login');
Route::post('/refresh',             'Api\Auth\AuthController@refresh');

//Secure routes (JWT)
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/tools',            'Api\ToolsController@index');
    Route::get('/tools/{tool}',     'Api\ToolsController@show');
    Route::post('/tools',           'Api\ToolsController@store');
    Route::patch('/tools/{tool}',   'Api\ToolsController@update');
    Route::delete('/tools/{tool}',  'Api\ToolsController@destroy');
});

Route::fallback(function(){
    return response()->json(['error' => 'API endpoint not found'], 404);
});