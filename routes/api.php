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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//JWT Token management
Route::post('/login',                   'Api\Auth\LoginController@login');
Route::post('/refresh',                 'Api\Auth\LoginController@refresh');

//Secure routes (JWT)
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/tools',                    'Api\ToolsController@index');
    Route::get('/tools/{tool}',             'Api\ToolsController@show');
    Route::post('/tools',                   'Api\ToolsController@store');
    Route::patch('/tools/{tool}',           'Api\ToolsController@update');
    Route::delete('/tools/{tool}',          'Api\ToolsController@destroy');
});

//Public routes
Route::get('/public-tools',             'ToolsController@index');
Route::get('/public-tools/{tool}',      'ToolsController@show');
Route::post('/public-tools',            'ToolsController@store');
Route::patch('/public-tools/{tool}',    'ToolsController@update');
Route::delete('/public-tools/{tool}',   'ToolsController@destroy');
