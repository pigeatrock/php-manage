<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Db;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class user extends Controller
{
    //测试
    public function test()
    {
        
    }
    //login
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        //$username = $_POST['username'];
        //$password = $_POST['password'];

        $data = DB::select('select * from user where name = ? and password = ?',[$username,$password]);

       /* $data = [
            "token" => 'super_admin',
            "code" => 20000,
            "message" => "login",
        ];*/
        if($data)
        {
            $token = $data[0]->token;
            return ["code" => 20000,"token" => $token,"message" => "登录成功"];
        }else{
            return ["code" => 200000,"message" => "请输入正确的用户名和密码"];
        }
    }
    //getinfo
    public function info()
    {
        $token = $_GET['token'];
        /*$data = [
            "code" => 20000,
            "roles" => ["super_admin"],
            "name" => "super_admin",
            "avatar" => "http://127.0.0.1:88/meitu/public/1.jpg",
            "message" => "getinfo",
        ];*/
        if($token)
        {
            $data = DB::select('select * from user where token = ?',[$token]);
            return ["code" => 20000,"roles" =>[$data[0]->roles],"name" => $data[0]->name,"id" => $data[0]->id,"website_name" => $data[0]->website_name,"avatar" => "http://127.0.0.1:88/meitu/public/1.jpg"];
        }else{
            return ["code" => 50008];
        }

    }
    //logout
    public function logout()
    {
        $data = [
            "code" => 20000,
            "message" => "logout"
        ];
        return $data;
    }
}
