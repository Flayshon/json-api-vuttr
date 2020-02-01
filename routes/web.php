<?php

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
    //return view('welcome');
    return 'VUTTR API Online';
});

Route::get('/tools',                'ToolsController@index');
Route::get('/tools/{tool}',         'ToolsController@show');
Route::post('/tools',               'ToolsController@store');
Route::patch('/tools/{tool}',       'ToolsController@update');
Route::delete('/tools/{tool}',      'ToolsController@destroy');