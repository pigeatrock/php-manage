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
 //获取饼状图
 public function getpie()
 {
     $id = $_GET['uid'];
     if($id==2){
         $storage_count = DB::select('select count(id) as count from customer where save_type=0');
         $submit_count = DB::select('select count(id) as count from customer where save_type=1' );
         return ['pie_value1'=>$storage_count[0]->count,'pie_value2'=>$submit_count[0]->count];
     }
     $storage_count = DB::select('select count(id) as count from customer where save_type=0 and uid =?',[$id]);
     $submit_count = DB::select('select count(id) as count from customer where save_type=1 and uid =?',[$id]);
     return ['pie_value1'=>$storage_count[0]->count,'pie_value2'=>$submit_count[0]->count];
 }
 //获取柱状图和折线图
 public function getchart()
 {
     $uid = $_GET['uid'];
     // $chart_days = date();
     $now_time = time();//当前时间戳
     $todaystamp=mktime(8,0,0,date('m'),date('d'),date('Y'));//今天8点时间戳
     $storage_count = [];//暂存统计[11,3,4,21,32,4,67]
     $submit_count = [];//提交统计
     $chart_days = [];//日期数组
     
     $where_str = ' ';
     if($uid!=2)
     {
         $where_str .= " and uid =$uid ";
     }
     //获取7天暂存统计数据
     $storage_count_reverse = [];
     for($i=0;$i<6;$i++)
     {
         $time1 = mktime(8,0,0,date('m'),date('d')-$i,date('Y'));
         $time2 = mktime(8,0,0,date('m'),date('d')-$i-1,date('Y'));
         $data1 = DB::select("select count(id) as tmp from customer where (start_time between ? and ?) and save_type = 0 $where_str",[$time2,$time1]);
         $storage_count_reverse[]=$data1[0]->tmp;
     }
     $storage_count = array_reverse($storage_count_reverse);
         //今天数量
     $now_storage_conut_tmp = DB::select("select count(id) as tmp from customer where (start_time between ? and ?) and save_type = 0 $where_str",[$todaystamp,$now_time]);
     $storage_count[]=$now_storage_conut_tmp[0]->tmp;

     //获取7天提交统计数据
     $submit_count_reverse = [];
     for($i=0;$i<6;$i++)
     {
         $time1 = mktime(8,0,0,date('m'),date('d')-$i,date('Y'));
         $time2 = mktime(8,0,0,date('m'),date('d')-$i-1,date('Y'));
         $data2 = DB::select("select count(id) as tmp from customer where (start_time between ? and ?) and save_type = 1 $where_str",[$time2,$time1]);
         $submit_count_reverse[]=$data2[0]->tmp;
     }
     $submit_count = array_reverse($submit_count_reverse);
     $now_submit_conut_tmp = DB::select("select count(id) as tmp from customer where (start_time between ? and ?) and save_type = 1 $where_str",[$todaystamp,$now_time]);
     $submit_count[]=$now_submit_conut_tmp[0]->tmp;
     // var_dump($submit_count);

     //获取日期
     $chart_days_reverse = [];//反向日期数组
     for($i=0;$i<7;$i++)
     {
         $timestamp = $now_time-24*60*60*$i;
         $chart_days_reverse[]= date('Y-m-d',$timestamp);
     }
     $chart_days = array_reverse($chart_days_reverse);
     
     return ['chart_days'=>$chart_days,'storage_data'=>$storage_count,'submit_data'=>$submit_count];
 }
    //得到最大id相关信息
    public function getid()
    {
        // $data = DB::select('select id from customer order by id desc limit 1');
        $number = DB::select('select service_number from customer where save_type=1 order by id desc limit 1');
        if($number){
            $tmp = explode("-",$number[0]->service_number);
            return $tmp;
        }else{
            return 0;
        }
        // return $data;
    }
    //添加客户信息_提交
    public function save()
    {
        //生成service_number
        $tmp_number = $this->getid();
        $now_date = date("Ymd"); 
        if($tmp_number != 0){
            $date_number = $tmp_number[1];//数据库中提交中最新时间
            $id_number = $tmp_number[2];//数据库中提交中最新id
            if($now_date>$date_number)
            {
                $id_now = "001";
                $service_number = 'dh-'.$now_date.'-'.$id_now;
            }else{
                $id_now_tmp = $id_number+1;
                $id_now = str_pad($id_now_tmp,3,"0",STR_PAD_LEFT);
                $service_number = 'dh-'.$now_date.'-'.$id_now;
            }    
        }else{
            $service_number = 'dh-'.$now_date.'-'.'001';
        }
       

        // $service_number = $_GET['service_number'];
        $qr_code = $_GET['qr_code'];
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
        $materiel_num = $_GET['materiel_num'];
        $new_imei1 = $_GET['new_imei1'];
        $new_imei2 = $_GET['new_imei2'];
        $operator = $_GET['operator'];
        $end_time = $start_time + 50*60;
        $save_type = $_GET['save_type'];
        $storage_time = time();
        $file_list = $_GET['file_list'];

        DB::insert('insert into customer(service_number,customer_name,phone,uid,customer_type,service_type,fault_info,phone_type,phone_version,act_time,phone_color,qr_code,imei1,imei2,start_time,repair_result,check_result,actual_fault,fault_code,materiel,materiel_num,new_imei1,new_imei2,end_time,operator,save_type,storage_time,file_list) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$service_number,$customer_name,$phone,$uid,$customer_type,$service_type,$fault_info,$phone_type,$phone_version,$act_time,$phone_color,$qr_code,$imei1,$imei2,$start_time,$repair_result,$check_result,$actual_fault,$fault_code,$materiel,$materiel_num,$new_imei1,$new_imei2,$end_time,$operator,$save_type,$storage_time,$file_list]);
    }
    //storage 暂存数据
    public function save_storage()
    {
        // $storage_id = $_GET['storage_id'];判断是否暂存
        $qr_code = $_GET['qr_code'];
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
        $materiel_num = $_GET['materiel_num'];
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
        $data = DB::update('update customer set customer_name =?,phone=?,customer_type=?,service_type=?,fault_info=?,phone_type=?,phone_version=?,act_time=?,phone_color=?,qr_code=?,imei1=?,imei2=?,repair_result=?,check_result=?,actual_fault=?,fault_code=?,materiel=?,materiel_num=?,new_imei1=?,new_imei2=?,storage_time=?,file_list=? where id=?',[$customer_name,$phone,$customer_type,$service_type,$fault_info,$phone_type,$phone_version,$act_time,$phone_color,$qr_code,$imei1,$imei2,$repair_result,$check_result,$actual_fault,$fault_code,$materiel,$materiel_num,$new_imei1,$new_imei2,$storage_time,$file_list,$storage_id]);
        return $data;
        }else{
            DB::insert('insert into customer(customer_name,phone,uid,customer_type,service_type,fault_info,phone_type,phone_version,act_time,phone_color,qr_code,imei1,imei2,start_time,repair_result,check_result,actual_fault,fault_code,materiel,materiel_num,new_imei1,new_imei2,end_time,operator,save_type,file_list,storage_time) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$customer_name,$phone,$uid,$customer_type,$service_type,$fault_info,$phone_type,$phone_version,$act_time,$phone_color,$qr_code,$imei1,$imei2,$start_time,$repair_result,$check_result,$actual_fault,$fault_code,$materiel,$materiel_num,$new_imei1,$new_imei2,$end_time,$operator,$save_type,$file_list,$storage_time]);
        }
    }
    //查询信息
    public function show()
    {
        $save_type = $_GET['save_type'];
        $uid = $_GET['uid'];
        if($uid == 2){
            // $data = DB::select('select * from customer order by end_time desc');
            $data = DB::table('customer')->leftJoin('user','customer.uid','=','user.id')->leftJoin('materiel','customer.materiel','=','materiel.id')->leftJoin('fault','customer.fault_code','=','fault.id')->select('customer.*','user.website_name','user.address_sheng','user.address_shi','materiel.materiel_name','fault.fault_express')->where('customer.save_type',$save_type)->orderBy('end_time', 'desc')->get();
            // $data = DB::table('customer')->leftJoin('fault','customer.fault_code','=','fault.id')->join('materiel','customer.materiel','=','materiel.id')->get();
        }else{
            $data = DB::table('customer')->leftJoin('user','customer.uid','=','user.id')->leftJoin('materiel','customer.materiel','=','materiel.id')->leftJoin('fault','customer.fault_code','=','fault.id')->where('customer.uid',$uid)->where('customer.save_type',$save_type)->select('customer.*','user.website_name','materiel.materiel_name','fault.fault_express')->orderBy('end_time', 'desc')->get();
            // $data = DB::select('select * from customer where uid = ? order by end_time desc',[$uid]);
        }
        return $data;
    }
    //初始化暂存数据
    public function show_storage()
    {
        $storage_id = $_GET['storage_id'];
        $obj = DB::table('customer')->where('id',$storage_id)->select()->get();
    //     $obj = (array)$obj;
    // foreach ($obj as $k => $v) {
    //     if (gettype($v) == 'resource') {
    //         return;
    //     }
    //     if (gettype($v) == 'object' || gettype($v) == 'array') {
    //         $obj[$k] = (array)object_to_array($v);
    //     }
    // } 
    //     if($obj['phone_version']){
    //         $obj['phone_version'] = explode(" ",$obj['phone_version']);
    //     }
    //     $obj['file_list'] = $obj['file_list'];
        return json_encode($obj[0]);
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
    //获得响应附件
    public function getfile()
    {
        $customer_id = $_GET['customer_id'];
        $data = DB::table('customer')->select('file_list')->where('id',$customer_id)->get();
        return $data;
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
    //搜索
    public function search()
    {
        if(isset($_GET['query_string'])){
            $query_string = $_GET['query_string'];
            $uid = $_GET['uid'];
            $search_str = ' customer.uid=user.id and customer.materiel=materiel.id and customer.fault_code = fault.id ';
            if($uid !=2)
            {
                $search_str .= " and uid=$uid ";
            }
            $search_str .= " and (customer.service_number like '%$query_string%' or customer.imei1 like '%$query_string%') ";
            $data =DB::select("select customer.*,user.address_sheng,user.address_shi,user.website_name,materiel.materiel_name,fault.fault_express from customer,user,materiel,fault where $search_str order by end_time DESC");
            return $data;
        }
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
        $where_str .= " and uid=$uid ";
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
        $data =DB::select("select customer.*,user.address_sheng,user.address_shi,user.website_name,materiel.materiel_name,fault.fault_express from customer,user,materiel,fault where $where_str order by end_time DESC");
        return $data;
    }
    //暂存条件查询
    public function storage_search()
    {
        $time1 = $_GET['time1']/1000;
        $time2 = $_GET['time2']/1000;
        $uid = $_GET['uid'];

        $where_str = ' customer.uid=user.id and customer.materiel=materiel.id and customer.fault_code = fault.id and customer.save_type=0 ';
        if($uid !=2)
        {
            $where_str .= " and uid=$uid ";
        }
        if($time1!='' && $time2!=''){
            $where_str .= " and (storage_time between $time1 and $time2)";  
        }elseif($time1=='' && $time2 ==''){
        }elseif($time1=='' && $time2 != ''){
            $where_str .= " and storage_time <= $time2";
        }elseif($time1 !='' && $time2 ==''){
            $where_str .= " and storage_time >= $time1";
        }
        // $data = DB::table('customer')->join('user','customer.uid','=','user.id')->join('materiel','customer.materiel','=','materiel.id')->join('fault','customer.fault_code','=','fault.id')->where('customer.uid',$uid)->select('customer.*','user.website_name','materiel.materiel_name','fault.fault_express')->orderBy('end_time', 'desc')->get();
        // echo $where_str;
        $data =DB::select("select customer.*,user.address_sheng,user.address_shi,user.website_name,materiel.materiel_name,fault.fault_express from customer,user,materiel,fault where $where_str order by storage_time DESC");
        return $data;
    }

}
