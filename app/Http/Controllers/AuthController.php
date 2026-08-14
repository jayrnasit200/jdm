<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;
class AuthController extends Controller
{
    public function index(Request $request)
    {
        $data["userlist"] =DB::table('users')->get();
        $data["usercount"] =DB::table('users')->get()->count();
        $data["usergoals"] =DB::table('goals as gol')
        ->join('users as use', 'use.id', '=', 'gol.user_id')
        ->select('gol.name as goalname', 'use.name as username', 'gol.target_amount', 'gol.saved_amount', 'gol.deadline')   
        ->get();
        // print($data["usergoals"]);
        // exit;
        return view('welcome')->with('data',$data);
        // return view('welcome');
    }
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register'); // Ensure you have a view at resources/views/auth/register.blade.php
    }
public function user(Request $request)
{
    return response()->json($request->user());
}
    // Register a new user
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            // return $th->validator->errors();
            return response()->json(['error' => $th->validator->errors()], 201);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
    public function registeradmin(Request $request)
    {
       
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
      
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('login');
    }
     public function loginadmin(Request $request) {
            // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Check if user is admin
            if ($user->is_admin == 'yes') {
                return redirect("/"); // Redirect to admin panel
            } else {
                return redirect()->route('login')->with('message', 'Sorry You are not an admin.'); // Redirect to user dashboard
            }
        }

        // If login fails, return back with error
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
      }
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure you have a view at resources/views/auth/login.blade.php
    }

    // Login a user
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            // return $th->validator->errors();
            return response()->json(['error' => $th->validator->errors()], 201);
        }
        

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => $user]);
    }

    // Logout a user
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
    public function logoutadmin(Request $request)
    {
        Auth::logout(); // Log out the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login')->with('success', 'You have been logged out.');
    
    }
    public function profileupdate(Request $request){
        $data= DB::table('users')->where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            "updated_at"=>now(),
        ]);
        // print_r($request->name);
        return response()->json(['message' => 'Profile updated successfully']);
    }
    public function Homepage(Request $request){
        $id =$request->id;
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
    
        return response()->json($data);
    }
}