<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class UserController extends Controller
{
    public function index() {
        $data =DB::table('users')->get();
        return view('UserList')->with('users',$data);
    }
    public function useradmin($id) {
        DB::table('users')->where('id', $id)->update([
            'is_admin' => "yes",]);
            return Redirect("/users")->with(['message' => 'users update as a Admin Successfully']);
    }
    public function userview($id) {
        // $id =$request->id;
        $data = [];
        $data['goals'] = DB::table('goals')->where('user_id', $id)->take(2)->get();
        
        $dataearns = DB::table('earn')->where('user_id', $id)->selectRaw('SUM(amount) as totalPrice')->get()->first();
        $data['TotalEarn']=$dataearns->totalPrice;
        $dataspending = DB::table('spending')->where('user_id', $id)->selectRaw('SUM(amount) as totalPrice')->get()->first();
        $data['Totalspending']=number_format((float)$dataspending->totalPrice, 2, '.', '');

        $data['rota'] = DB::table('earn_rota')
        ->join('earn_joblist', 'earn_joblist.id', '=', 'earn_rota.job_id')
        ->where('earn_joblist.user_id', $id)
        ->where('earn_rota.status', "pending")
        ->where('earn_rota.date', '>=', DB::raw('CURDATE()')) // Ensure date is today or in the future
        ->where(function($query) {
            $query->where('earn_rota.Date', '>', DB::raw('CURDATE()'))
                  ->orWhere('earn_rota.sTime', '>=', DB::raw('CURTIME()')); // Ensure time is also in the future
        })
        ->orderBy('earn_rota.Date', 'asc') // Order by date ascending
        ->orderBy('earn_rota.sTime', 'asc') // Order by time ascending
        ->take(2)
        ->get();
    
        return view('Userview')->with('data',$data);

    }
}
