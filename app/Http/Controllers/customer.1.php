<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Db;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class customer extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //添加客户信息_提交
    public function save()
    {
        $act_time = $_GET['act_time'];
        $customer_name = $_GET['customer_name'];
        $phone = $_GET['phone'];
        $uid = $_GET['uid'];
        $customer_type = $_GET['customer_type'];
        $service_type = $_GET['service_type'];
        $fault_info = $_GET['fault_info'];
        if(isset($_GET['phone_version'][0])){
            $phone_type = $_GET['phone_version'][0];
        }else{
            $phone_type = null;
        }
       if(isset($_GET['phone_version'][1])){
            $phone_version = $_GET['phone_version'][1];
       }else{
           $phone_version = null;
       }
        $phone_color = $_GET['phone_color'];
        $imei1 = $_GET['imei1'];
        $imei2 = $_GET['imei2'];
        $start_time = time();
        $repair_result = $_GET['repair_result'];
        $check_result = $_GET['check_result'];
        $actual_fault = $_GET['actual_fault'];
        $fault_code = $_GET['fault_code'];
        $materiel = $_GET['materiel'];
        $new_imei1 = $_GET['new_imei1'];
        $new_imei2 = $_GET['new_imei2'];
        $operator = $_GET['operator'];
        $end_time = $start_time + 50*60;
        $save_type = $_GET['save_type'];
        $storage_time = time();
        $file_list = $_GET['file_list'];

        DB::insert('insert into customer(customer_name,phone,uid,customer_type,service_type,fault_info,phone_type,phone_version,act_time,phone_color,imei1,imei2,start_time,repair_result,check_result,actual_fault,fault_code,materiel,new_imei1,new_imei2,end_time,operator,save_type,storage_time,file_list) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$customer_name,$phone,$uid,$customer_type,$service_type,$fault_info,$phone_type,$phone_version,$act_time,$phone_color,$imei1,$imei2,$start_time,$repair_result,$check_result,$actual_fault,$fault_code,$materiel,$new_imei1,$new_imei2,$end_time,$operator,$save_type,$storage_time,$file_list]);
    }
    //storage 暂存数据
    public function save_storage()
    {
        // $storage_id = $_GET['storage_id'];判断是否暂存
        $act_time = $_GET['act_time'];
        $customer_name = $_GET['customer_name'];
        $phone = $_GET['phone'];
        $uid = $_GET['uid'];
        $customer_type = $_GET['customer_type'];
        $service_type = $_GET['service_type'];
        $fault_info = $_GET['fault_info'];
        if(isset($_GET['phone_version'][0])){
            $phone_type = $_GET['phone_version'][0];
        }else{
            $phone_type = null;
        }
       if(isset($_GET['phone_version'][1])){
            $phone_version = $_GET['phone_version'][1];
       }else{
           $phone_version = null;
       }
        $phone_color = $_GET['phone_color'];
        $imei1 = $_GET['imei1'];
        $imei2 = $_GET['imei2'];
        $start_time = time();
        $repair_result = $_GET['repair_result'];
        $check_result = $_GET['check_result'];
        $actual_fault = $_GET['actual_fault'];
        $fault_code = $_GET['fault_code'];
        $materiel = $_GET['materiel'];
        $new_imei1 = $_GET['new_imei1'];
        $new_imei2 = $_GET['new_imei2'];
        $operator = $_GET['operator'];
        $end_time = $start_time + 50*60;
        $save_type = $_GET['save_type'];
        $storage_time = time();
        $file_list = $_GET['file_list'];
       var_dump($file_list);
        if($_GET['storage_id'] != ''){
            $storage_id = $_GET['storage_id'];
            //id,uid,start_time,end_time
            //storage_time
        $data = DB::update('update customer set customer_name =?,phone=?,customer_type=?,service_type=?,fault_info=?,phone_type=?,phone_version=?,act_time=?,phone_color=?,imei1=?,imei2=?,repair_result=?,check_result=?,actual_fault=?,fault_code=?,materiel=?,new_imei1=?,new_imei2=?,storage_time=?,file_list=? where id=?',[$customer_name,$phone,$customer_type,$service_type,$fault_info,$phone_type,$phone_version,$act_time,$phone_color,$imei1,$imei2,$repair_result,$check_result,$actual_fault,$fault_code,$materiel,$new_imei1,$new_imei2,$storage_time,$file_list,$storage_id]);
        return $data;
        }else{
            DB::insert('insert into customer(customer_name,phone,uid,customer_type,service_type,fault_info,phone_type,phone_version,act_time,phone_color,imei1,imei2,start_time,repair_result,check_result,actual_fault,fault_code,materiel,new_imei1,new_imei2,end_time,operator,save_type,file_list,storage_time) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$customer_name,$phone,$uid,$customer_type,$service_type,$fault_info,$phone_type,$phone_version,$act_time,$phone_color,$imei1,$imei2,$start_time,$repair_result,$check_result,$actual_fault,$fault_code,$materiel,$new_imei1,$new_imei2,$end_time,$operator,$save_type,$file_list,$storage_time]);
        }
    }
    //查询信息
    public function show()
    {
        $save_type = $_GET['save_type'];
        $uid = $_GET['uid'];
        if($uid == 2){
            // $data = DB::select('select * from customer order by end_time desc');
            $data = DB::table('customer')->leftJoin('user','customer.uid','=','user.id')->leftJoin('materiel','customer.materiel','=','materiel.id')->leftJoin('fault','customer.fault_code','=','fault.id')->select('customer.*','user.website_name','materiel.materiel_name','fault.fault_express')->where('customer.save_type',$save_type)->orderBy('end_time', 'desc')->get();
            // $data = DB::table('customer')->leftJoin('fault','customer.fault_code','=','fault.id')->join('materiel','customer.materiel','=','materiel.id')->get();
        }else{
            $data = DB::table('customer')->leftJoin('user','customer.uid','=','user.id')->leftJoin('materiel','customer.materiel','=','materiel.id')->leftJoin('fault','customer.fault_code','=','fault.id')->where('customer.uid',$uid)->where('customer.save_type',$save_type)->select('customer.*','user.website_name','materiel.materiel_name','fault.fault_express')->orderBy('end_time', 'desc')->get();
            // $data = DB::select('select * from customer where uid = ? order by end_time desc',[$uid]);
        }
        return $data;
    }
    //判断是否已经暂存
    public function ifstorage()
    {
        if(isset($_GET['storage_id'])){
            $storage_id = $_GET['storage_id'];
         $data = DB::table('customer')->where('id',$storage_id)->get();
        }
        
        if($data)
        {
            return 1;//已存在数据
        }else{
            return 0;
        }
    }
    //初始化暂存数据
    public function show_storage()
    {
        $storage_id = $_GET['storage_id'];
        $obj = DB::table('customer')->where('id',$storage_id)->first();
     

        $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    } 
        if($obj['phone_version']){
            $obj['phone_version'] = explode(" ",$obj['phone_version']);
        }
        $obj['file_list'] = $obj['file_list'];
        return $obj;
    }
    //上传附件 blob
    public function upload(Request $request)
    {
        // var_dump($_FILES['file']);
        if($request->isMethod('post')){
            $file = $request->file('file');
            //文件是否上传成功
            if($file->isValid())
            {
                $originalName = $file->getClientOriginalName();//文件原名
                $ext = $file->getClientOriginalExtension(); //扩展名
                $realPath = $file->getRealPath();
                $type = $file->getClientMimeType(); 
                //上传文件
                $filename = time().'-'.uniqid().'.'.$ext;
                $bool = Storage::disk('uploads')->put($filename,\file_get_contents($realPath));
                // var_dump($bool);
                //相对路径 http://127.0.0.1:88/meitu/public/uploads/1543654923-5c024e0b53414.jpg
                $path = url().'/'.'uploads/'.$filename;
                return ['name'=>$originalName,'url'=>$path];
                // return $path;
            }
        }

    }
    //打印
    public function print()
    {
        $info_id = $_GET['info_id'];
        //$data = DB::table('customer')->leftJoin('user','customer.uid','=','user.id')->where('customer.id','=',$info_id)->get();
        //$data = DB::select('select * from customer where id = ?',[$info_id]);
        $data = DB::table('customer')->leftJoin('user','customer.uid','=','user.id')->join('materiel','customer.materiel','=','materiel.id')->where('customer.id','=',"$info_id")->select()->get();
        return $data;
    }
    //删除
    public function del()
    {
        $id = $_GET['id'];
        DB::table('customer')->where('id','=',$id)->delete();
    }
    //条件查询
    public function query()
    {
        $time1 = $_GET['time1']/1000;
        $time2 = $_GET['time2']/1000;
        $uid = $_GET['uid'];
        $phone_type = $_GET['phone_type'];

        // $where_str = ' 1=1 ';
        $where_str = 'customer.uid=user.id and customer.materiel=materiel.id and customer.fault_code = fault.id ';
       
        if($uid != 2)
        {
        $where_str .= ' and uid=$uid ';
        }
        if($phone_type!= ''){
            $where_str .= " and customer.phone_type = '$phone_type'";
        }
        if($time1!='' && $time2!=''){
            $where_str .= " and (end_time between $time1 and $time2)";  
        }elseif($time1=='' && $time2 ==''){
        }elseif($time1=='' && $time2 != ''){
            $where_str .= " and end_time <= $time2";
        }elseif($time1 !='' && $time2 ==''){
            $where_str .= " and end_time >= $time1";
        }
        // $data = DB::table('customer')->join('user','customer.uid','=','user.id')->join('materiel','customer.materiel','=','materiel.id')->join('fault','customer.fault_code','=','fault.id')->where('customer.uid',$uid)->select('customer.*','user.website_name','materiel.materiel_name','fault.fault_express')->orderBy('end_time', 'desc')->get();
        // echo $where_str;
        $data =DB::select("select customer.*,user.website_name,materiel.materiel_name,fault.fault_express from customer,user,materiel,fault where $where_str order by end_time DESC");
        return $data;
    }

}
