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
//获取故障代码
Route::any('getfault','fault@show');
//获得phone type列表
Route::any('gettypeoptions','phone@typeoptions');
//获得phone version一级菜单
Route::any('getphoneversion1','phone@show1');
//获得phone version二级菜单
Route::any('getphoneversion2','phone@show2');
//获得手机型号
Route::any('getphonetype','phone@type');
//获得materiel的列表
Route::any('getmateriel','materiel@show');
//获取物料名称
Route::any('materielname','materiel@getname');
//查询信息
Route::get('show','customer@show');
//筛选
Route::get('query','customer@query');
//登录
Route::any('user/login','user@login');
//获取用户信息
Route::any('user/info','user@info');
//获取账号列表
Route::any('showuser','user@show');
//编辑账号
Route::any('edituser','user@edit');
//添加账号
Route::any('adduser','user@add');
//删除账号
Route::any('deluser','user@del');
//打印
Route::any('print','customer@print');
//删除记录
Route::any('del','customer@del');
//退出登录
Route::any('user/logout','user@logout');

//测试
Route::any('test','user@test');