<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function signinpage(Request $request) {
        if (Auth::check()) {
            return redirect('/domains'); // Already logged in, go to dashboard
        }
        return view('signin');
    }
    public function authenticate(Request $request) {
       
        // $data = array(
        //     "username" => $username,
        //     "password" => $password,
        //     "protocol" => "API",
        //     "scope" => "master",
        //     "token" => true
        // );
        // $authentication_url = env('WILDDUCK_URL')."/authenticate";
        // $authenticate = curlPost($authentication_url,$data);
        // $result = json_decode($authenticate, true);


        // Attempt to log the user in
        if (Auth::attempt([
            'email' => $request->input('username'),
            'password' => $request->input('password')
        ])) {
            $request->session()->regenerate();
            return redirect()->route('domains'); 
        }

        // If authentication fails, return back with an error
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    public function signup(Request $request) {
        return view('signup');
    }
    public function registar(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // 'password' => 'required|string|min:8|confirmed', // Password must match 'password_confirmation'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hashedPassword = bcrypt($request->input('password'));
        // Create the new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashedPassword,
        ]);

        // Log the user in after registration
        // auth()->login($user);

        // Redirect to home or dashboard
        return redirect()->route('login');
    }
}
