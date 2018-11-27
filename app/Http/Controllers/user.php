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
            return ["code" => 20000,"roles" =>[$data[0]->roles],"name" => $data[0]->name,"id" => $data[0]->id,"website_name" => $data[0]->website_name,"address" => $data[0]->address, "website_phone"=>$data[0]->website_phone, "avatar" => "http://127.0.0.1:88/meitu/public/1.jpg"];
        }else{
            return ["code" => 50008];
        }

    }
    //显示账号列表
    public function show()
    {
        $data = DB::table('user')->whereNotIn('id',[2])->get();
        return ["code" => 20000,"data"=>$data];
    }
    //编辑账号
    // $edit_info = array([
    //     id => 1,
    //     name => 'test',
    // ]);
    public function edit()
    {
        $edit_info = json_decode($_GET['edit_info']);
        $id = $edit_info->id;
        $name = $edit_info->name;
        $password = $edit_info->password;
        $website_name = $edit_info->website_name;
        $address = $edit_info->address;
        $website_phone = $edit_info->website_phone;
        // DB::table('user')->where('id',$edit_info.id)
        //     ->update(['name'=>$edit_info.name,'password'=>$edit_info.password,'website_name'=>$edit_info.website_name,'address'=>$edit_info.address,'website_phone'->$edit_info.website_phone]);
        DB::table('user')->where('id','=',$id)->update(
            ['name'=>$name,'password'=>$password,'website_name'=>$website_name,'address'=>$address,'website_phone'=>$website_phone]);

        return ["code"=>20000,
                "message" => "edituser"
        ];
        // echo DB::table('user')->where('id',$edit_info['id'])->get();
    }
    //添加账号
    public function add()
    {
        $user_info = json_decode($_GET['user_info']);
        $time = time();
        DB::table('user')->insert(
            ['website_name'=> $user_info->website_name, 'address'=>$user_info->address,'website_phone'=>$user_info->website_phone,'name'=>$user_info->name,'password'=>$user_info->password,'token'=>$time ]
        );
        $data = [
            "code" => 20000,
            "message" => "adduser"
        ];
        return $data;
    }
    //删除账号
    public function del()
    {
        $id = $_GET['id'];
        DB::table('user')->where('id','=',$id)->delete();

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
