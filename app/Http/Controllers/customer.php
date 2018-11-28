<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Db;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class customer extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //添加客户信息
    public function save()
    {
        $customer_name = $_GET['customer_name'];
        $phone = $_GET['phone'];
        $uid = $_GET['uid'];
        $customer_type = $_GET['customer_type'];
        $service_type = $_GET['service_type'];
        $fault_info = $_GET['fault_info'];
        $phone_version = $_GET['phone_version'][1];
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
        $end_time = $start_time + 50*60;

        DB::insert('insert into customer(customer_name,phone,uid,customer_type,service_type,fault_info,phone_version,phone_color,imei1,imei2,start_time,repair_result,check_result,actual_fault,fault_code,materiel,new_imei1,new_imei2,end_time) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$customer_name,$phone,$uid,$customer_type,$service_type,$fault_info,$phone_version,$phone_color,$imei1,$imei2,$start_time,$repair_result,$check_result,$actual_fault,$fault_code,$materiel,$new_imei1,$new_imei2,$end_time]);
    }
    //查询信息
    public function show()
    {
        $uid = $_GET['uid'];
        if($uid == 2){
            $data = DB::select('select * from customer order by end_time desc');
            // $data = DB::table('customer')->leftJoin('fault','customer.fault_code','=','fault.id')->join('materiel','customer.materiel','=','materiel.id')->get();
        }else{
            $data = DB::select('select * from customer where uid = ? order by end_time desc',[$uid]);
        }
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
    //条件查询
    public function query()
    {
        $time1 = $_GET['time1'];
        $time2 = $_GET['time2'];
        $uid = $_GET['uid'];
        $phone_version = $_GET['phone_version'];
        if($uid == 2)
        {
            if($phone_version == ''){
                $data =DB::select('select * from customer where (end_time between ? and ?)',[$time1,$time2]);
            return $data;
            }elseif($time2==''){
                $data =DB::select('select * from customer where  phone_version = ?',[$phone_version]);
            return $data;
            }else{
                $data =DB::select('select * from customer where (end_time between ? and ?) and  phone_version = ?',[$time1,$time2,$phone_version]);
            return $data;
            }
        }else{
            if($phone_version == ''){
                $data =DB::select('select * from customer where (end_time between ? and ?) and uid =?',[$time1,$time2,$uid]);
            return $data;
            }elseif($time2==''){
                $data =DB::select('select * from customer where  phone_version = ? and uid =?',[$phone_version,$uid]);
            return $data;
            }else{
                $data =DB::select('select * from customer where (end_time between ? and ?) and  phone_version = ? and uid = ?',[$time1,$time2,$phone_version,$uid]);
            return $data;
            }
        }
        // if($phone_version == ''){
        //     $data =DB::select('select * from customer where (end_time between ? and ?) and uid =?',[$time1,$time2,$uid]);
        // return $data;
        // }elseif($time2==''){
        //     $data =DB::select('select * from customer where  phone_version = ? and uid =?',[$phone_version,$uid]);
        // return $data;
        // }else{
        //     $data =DB::select('select * from customer where (end_time between ? and ?) and  phone_version = ? and uid = ?',[$time1,$time2,$phone_version,$uid]);
        // return $data;
        // }
        //$all_data = DB::table('customer')->where('start_time','=','$time1')->where('end_time','=','$time2')->where('phone_version','=','$phone_version')->select()->get();
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
