<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\Spending;
class SpendingsController extends Controller
{
    public function index(Request $request)
    {
        // print_r($request->get('id'));
        $data = DB::table('spending')->where('user_id', $request->get('id'))->get();
        // if (empty($data)) {
            
        // }
        return response()->json(['data' => $data], 200);
    }
    public function create(Request $request){
       
        try {
            $request->validate([
                'amount' => 'required|numeric|between:0,9999999999.99',
                'cat_id' => 'required',
                'description' => 'required',
                'user_id' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            // return $th->validator->errors();
            return response()->json(['error' => $th->validator->errors()], 201);
        }
        
        $user = DB::table('spending')->insert([
            'amount' => $request->amount,
            'cat_id' => $request->cat_id,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'Date' => $request->date,
            "created_at"=>now(),
            "updated_at"=>now(),
        ]);
        return response()->json(['message' => 'Spending `created successfully'], 200);
    
    }
    function delete($id) {
        $data = DB::table('spending')->where('id', $id)->delete();
        return response()->json(['message' => 'Spending Deleted Successfully '], 200);
    }
    public function edit($id) {
        $data = DB::table('spending')->where('id', $id)->get()->first();
        return response()->json(['data' => $data], 200);
    }
    public function update(Request $request){
    //   print_r($request->all());
    //    exit;
        // try {
        //     $request->validate([
        //         'amount' => 'required|numeric|gt:0',
        //         'cat_id' => 'required',
        //         'description' => 'required',
        //     ]);
        // } catch (\Illuminate\Validation\ValidationException $th) {
        //     // return $th->validator->errors();
        //     return response()->json(['error' => $th->validator->errors()], 201);
        // }
        // \DB::enableQueryLog(); 
        $user = DB::table('spending')->where('id', $request->id)->update([
            'amount' => $request->amount,
            'cat_id' => $request->cat_id,
            'description' => $request->description,
            // 'user_id' => $request->user_id,
            // 'Date' => $request->date,
            // "created_at"=>now(),
            "updated_at" => now(),
        ]);
        // dd(\DB::getQueryLog());    
        return response()->json(['message' => 'Update successfully'], 200);
    
    }
}
