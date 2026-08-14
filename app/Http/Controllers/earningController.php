<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class earningController extends Controller
{
    public function index(Request $request){
        if($request->id){
            $earnings = DB::table('earn')->where('job_id', $request->id)->get();

        
        }else{
            $earnings = DB::table('earn')->get();
        }
        return response()->json(['data' => $earnings], 200);
    }

    public function getearningsnyuser(Request $request) {
        $earnings = DB::table('earn')->where('user_id', $request->id)->get();
        return response()->json(['data' => $earnings], 200);
    }
}
