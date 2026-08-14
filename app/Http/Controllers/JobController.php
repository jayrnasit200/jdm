<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request) {
        // print_r($request);
        // exit;
        $data = DB::table('earn_joblist')->where('user_id', $request->get('id'))->get();
      $datas = [];
        foreach ($data as $key => $value) {
            $datas[$key] = [ "id"=> $value->id,
            "user_id"=> $value->user_id,
            "Job_title"=> $value->Job_title,
            "pay_rate"=> number_format((float)$value->pay_rate, 2, '.', ''),
            "description"=> $value->description,];
      }
        return response()->json(['data' => $datas], 200);
    }
    public function create(Request $request) {
    //  print_r($request->post());
    //     exit;
        try {
            $request->validate([
                'user_id' => 'required',
                'payrate' => 'required|numeric|gt:0',
                'Jobtitle' => 'required',
                'description' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            // return $th->validator->errors();
            return response()->json(['error' => $th->validator->errors()], 201);
        }
        
        $user = DB::table('earn_joblist')->insert([
            'user_id' => $request->user_id,
            'Job_title' => $request->Jobtitle,
            'pay_rate' => $request->payrate,
            'description' => $request->description,
            "created_at"=>now(),
            "updated_at"=>now(),
        ]);
        return response()->json(['message' => ' successfully'], 200);
    }
    function delete($id) {
        $data = DB::table('earn_joblist')->where('id', $id)->delete();
        return response()->json(['message' => ' successfully Deleted'], 200);
    }
    function edit($id) {
        $data = DB::table('earn_joblist')->where('id', $id)->get()->first();
        $data->pay_rate= number_format((float)$data->pay_rate, 2, '.', '');
       
        return response()->json(['data' => $data], 200);
    }
    public function update(Request $request){
  
        try {
            $request->validate([
                'user_id' => 'required',
                'payrate' => 'required|numeric|gt:0',
                'Jobtitle' => 'required',
                'description' => 'required',
                'id' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            // return $th->validator->errors();
            return response()->json(['error' => $th->validator->errors()], 201);
        }
        
        $user = DB::table('earn_joblist')->where('id', $request->id)->update([
            'user_id' => $request->user_id,
            'Job_title' => $request->Jobtitle,
            'pay_rate' => $request->payrate,
            'description' => $request->description,
            "updated_at"=>now(),
        ]);
        return response()->json(['message' => 'Update successfully'], 200);
    
    }
    
}
