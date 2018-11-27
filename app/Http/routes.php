<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


//Route::get('user',['index'=>'UserController@index']);

//添加客户信息
Route::get('save','customer@save');
//查询信息
Route::get('show','customer@show');
//筛选
Route::get('query','customer@query');
//登录
Route::any('user/login','user@login');
//获取用户信息
Route::any('user/info','user@info');
//打印
Route::any('print','customer@print');
//删除
Route::any('del','customer@del');
//退出登录
Route::any('user/logout','user@logout');

//测试
Route::any('test','user@test');