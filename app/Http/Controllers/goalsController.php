<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class goalsController extends Controller
{
    public function index(Request $request){
       $data = DB::table('goals')->where('user_id', $request->get('id'))->get();
       // print_r($request->target_amount);
        // exit;
       return response()->json($data);

    }
    public function create(Request $request){
        // print_r($request->target_amount);
        // exit;
        try {
            $request->validate([
                'user_id' => 'required',
                'target_amount' => 'required',
                // 'saved_amount' => 'required',
                'name' => 'required',
                'deadline' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            // return $th->validator->errors();
            return response()->json(['error' => $th->validator->errors()], 201);
        }

        $user = DB::table('goals')->insert([
            'user_id' => $request->user_id,
            'target_amount' => $request->target_amount,
            'saved_amount' => 0,
            'name' => $request->name,
            'deadline' => $request->deadline,
            "created_at"=>now(),
            "updated_at"=>now(),
        ]);
        return response()->json(['message' => 'Goal created successfully'], 200);
    }
    public function goalcontri(Request $request) {
        $goals = DB::table('goals')->where('id', $request->get('goal_id'))->get()->first();
        $newsave = $goals->saved_amount + $request->get('amount');
        if ($newsave <= $goals->target_amount) {

        $user = DB::table('goals_contribution')->insert([
            'amount' => $request->amount,
            'notes' => $request->note,
            'goal_id' => $request->goal_id,
            'date' => $request->date,
            "created_at"=>now(),
            "updated_at"=>now(),
        ]);
        DB::table('goals')->where('id', $request->get('goal_id'))->update(['saved_amount' => $newsave,]);
        return response()->json(['message' => 'Goal contribution added successfully'], 200);
        }else{
            return response()->json(['message' => 'Goal contribution failed'], 401);
        }

        // print_r($goals->);
        // exit;
        // return response()->json(['message' => 'Goal Contribution created successfully'], 200);
    }
    public function goalcontriList(Request $request) {
        $data = DB::table('goals_contribution')->where('goal_id', $request->id)->get();
        return response()->json($data);
    }
    public function goalupdate(Request $request) {
        $user = DB::table('goals')->where('id', $request->id)->update([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            "updated_at"=>now(),
        ]);
        return response()->json(['message' => 'Goal updated successfully'], 200);
    }
}
