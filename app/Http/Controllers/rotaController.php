<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class rotaController extends Controller
{
    public function index(Request $request)
    {
        $datas = $request->all();
       
      foreach($datas['rota'] as $d){
        if (DB::table('earn_rota')->where([
            ['job_id', '=', $d['job']],
            ['Date', '=', $d['date']],
        ])->exists()){
           $data = DB::table('earn_rota')->where([
            ['job_id', '=', $d['job']],
            ['Date', '=', $d['date']], 
            ])->get()->first();
            DB::table('earn_rota')->where('id', $data->id)->update([
                'Date' => $d['date'],
                'sTime' => $d['start_time'],
                'eTime' => $d['end_time'],
                'status' => $data->status,
                "updated_at"=>now(),
            ]);

        }else{
            if($d['start_time'] != $d['end_time']){
                DB::table('earn_rota')->insert([
                    'job_id' => $d['job'],
                    'Date' => $d['date'],
                    'sTime' => $d['start_time'],
                    'eTime' => $d['end_time'],
                    'status' => "pending",
                    "created_at"=>now(),
                    "updated_at"=>now(),
                ]);
            }
           
        }
    
      }
      return response()->json(['message' => 'Rota Add successfully'], 200);
    }
    public function getrota(Request $request){
        $data = $request->all();
        
        $rota = DB::table('earn_rota')->where('job_id', $request->job)->orderBy('Date', 'asc')->get();
     
        return response()->json($rota);

        // return response()->json(['data' => $rota], 200);
    }
    public function updatestatus(Request $request)
        {
            DB::table('earn_rota')->where('id', $request->id)->update([
                // 'Date' => $request->date,
                'sTime' => $request->startTime,
                'eTime' => $request->endTime,
                'status' => $request->status,
                "updated_at"=>now(),
            ]);

            
            if($request->status=="completed"){
               
                $start = Carbon::parse($request->startTime);
                $end = Carbon::parse($request->endTime);
                $totalMinutes = $start->diffInMinutes($end)/60;
                $job = DB::table('earn_joblist')->where('id', $request->jobid)->get()->first();
                $earnamount = $totalMinutes * $job->pay_rate;
                DB::table('earn')->insert([
                    "date_earned"=>$request->date,
                    'amount' => $earnamount,
                    'user_id' => $job->user_id,
                    'category' => "Job",
                    'source' => "Salary",
                    'job_id' => $request->jobid,
                    "created_at"=>now(),
                    "updated_at"=>now(),
                ]);
            }
            // print_r($totalMinutes);
            // exit;
            return response()->json(['message' => 'Status updated successfully'], 200);
        }
}