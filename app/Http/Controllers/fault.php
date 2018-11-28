<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Db;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class fault extends Controller
{
    public function show()
    {
        // $id = DB::table('fault')->select('id')->get();
        // $fault_code = DB::table('fault')->select('fault_code')->get();
        // $fault_express = DB::table('fault')->select('fault_express')->get();
        // var_dump(["code"=>20000,"id"=>$id,"fault_code"=>$fault_code,"fault_express"=>$fault_express]);
        $data = DB::select('select id,concat(fault_code," ",fault_express) as fault_code from fault');
        return $data;
    }

}