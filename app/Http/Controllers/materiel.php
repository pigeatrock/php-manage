<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Db;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class materiel extends Controller
{
    public function show()
    {
        $phone_type = $_GET['phone_type'];
        $data = DB::select('select id,concat(materiel_code," ",materiel_name) as value from materiel where phone_type = ?',[$phone_type]);
        return $data;
    }
    //获取物料名称
    public function getname()
    {
        $id = $_GET['id'];
        $data = DB::table('materiel')->select('materiel_name')->where('id',$id)->get();
        return $data;
    }

}