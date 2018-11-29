<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Db;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class phone extends Controller
{
    /*public function show()
    {
        // $data = DB::table('phone')->get();
        // return $data;
        
        // $title = DB::table('phone')->pluck('phone_type');
        // var_dump($title);
        // $index = DB::table('phone')->select('phone_type')->distinct()->get();
        // return $index;
        
        //取出一级
        $first = [];
        $first_tmp = DB::table('phone')->select('phone_type')->distinct()->get();
        foreach($first_tmp as $tmp)
        {
            $first[] = $tmp->phone_type;
        }
        // return $first; ["m6","m6s","m8","m8t","m8s","t8","t8s","v6","t9","t9\u7279\u522b\u7248"]
        //二级
        $second = [];
        $fin = [];
        $second_tmp = DB::table('phone')->select('phone_version')->distinct()->get();
        foreach($second_tmp as $tmp)
        {
            $second[] = $tmp->phone_version;
        }
        // return $second;
        //分组二级
        for($i=0;$i<count($first);$i++)
        {
            foreach($second as $tmp)
            {
                if(stripos($tmp,$first[$i])!==false)
                {
                    // array_push($fin,$tmp);
                    $fin[$first[$i]][] = $tmp;
                }
            }
        }
        //循环push
        foreach($fin as $tmp)
        {
            var_dump($tmp);
        }

        // var_dump($first);
        // var_dump($fin);
        // $arr = [
        //     ['value'=>'test1','children'=>[['value'=>'test11','value'=>'test12']]],
        //     ['value'=>'test2','children'=>[['value'=>'test21','value'=>'test22']]]
        // ];
        // return $arr;

        // return ["code"=>20000,"data"=>$fin];
    }*/
    //获得手机型号列表
    public function typeoptions()
    {
        //取出一级
        $first = [];
        $first_tmp = DB::table('phone')->select('phone_type')->distinct()->get();
        foreach($first_tmp as $tmp)
        {
            $first[] = $tmp->phone_type;
        }
        foreach($first as $tmp)
        {
            $arr[] = ['value'=>$tmp,'label'=>$tmp];
        }
        return $arr;
    }
    public function show1()
    {
        //取出一级
        $first = [];
        $first_tmp = DB::table('phone')->select('phone_type')->distinct()->get();
        foreach($first_tmp as $tmp)
        {
            $first[] = $tmp->phone_type;
        }
        // return $first; ["m6","m6s","m8","m8t","m8s","t8","t8s","v6","t9","t9\u7279\u522b\u7248"]

        foreach($first as $tmp)
        {
            $arr[] = ['value'=>$tmp,'label'=>$tmp,'phones'=>[]];
        }
        // return $arr;
        return ["code"=>20000,"data"=>$arr];
    }
    //phone version 二级菜单
    public function show2()
    {
        $fin = [];
        $second = $_GET['second'];
        $second_tmp = DB::select('select phone_version from phone where phone_type = ?',[$second]);
        foreach($second_tmp as $tmp)
        {
            $fin[] = $tmp->phone_version;
        }
        // return $fin; ["m6","m6s","m8","m8t","m8s","t8","t8s","v6","t9","t9\u7279\u522b\u7248"]
        foreach($fin as $tmp)
        {
            $arr[] = ['label'=>$tmp];
        }
        return ["code"=>20000,"data"=>$arr];
    }
    //获得手机型号
    public function type()
    {
        $phone_version = $_GET['phone_version'];
        $data = DB::table('phone')->select('phone_type')->where('phone_version',$phone_version)->get();
        return $data;
    }

}