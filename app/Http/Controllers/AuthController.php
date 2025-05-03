<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\WildDuckUser;
use App\Models\WildduckAccesstoken;
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
       
        


        // Attempt to log the user in
        if (Auth::attempt([
            'email' => $request->input('username'),
            'password' => $request->input('password')
        ])) {
            $wildduck_authenticate = $this->authenticate_to_wildduck($request->input('username'),$request->input('password'));
            $result = json_decode($wildduck_authenticate, true);
            if(isset($result['success']) && $result['success']){
                $update_access_token = WildduckAccesstoken::updateOrCreate(
                    ['user_id'=>Auth::user()->id, 'wildduck_userid' => $result['id']],
                    ['access_token' => $result['token']]
                );
            }

            $request->session()->regenerate();
            return redirect()->route('domains'); 
        }

        // If authentication fails, return back with an error
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    public function authenticate_to_wildduck($username,$password) {
        $data = array(
            "username" => $username,
            "password" => $password,
            "protocol" => "API",
            "scope" => "master",
            "token" => true
        );
        $authentication_url = env('WILDDUCK_URL')."/authenticate";
        $header = array(
            'Content-Type: application/json'
        );
        $authenticate = curlPost($authentication_url,$data,$header);
        return $authenticate;
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
        $createwildduck_user = $this->createwildduck_user($request->input('email'),$request->input('password'));
        $result = json_decode($createwildduck_user, true);
        if(isset($result['success']) && $result['success']){
            $add_user = new WildDuckUser();
            $add_user->user_id = $user->id;
            $add_user->username = $request->input('email');
            $add_user->password = $request->input('password');
            $add_user->wildduck_userid = $result['id'];
            $add_user->default_mail = $request->input('email');
            $add_user->save();
        }
        return redirect()->route('login');
    }
    public function createwildduck_user($email,$password) {
        $data = array(
            "username" => $email,
            "password" => $password,
            "hashedPassword" => false,
            "allowUnsafe" => true,
            "address" => $email
        );
        $url = env('WILDDUCK_URL')."/users";
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json',
            // 'X-Access-Token: '.$access_token->access_token
        );
        $create_user = curlPost($url,$data,$header);
        return $create_user;
    }

    public function zoneAuth(Request $request) {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Basic ')) {
            return response('Unauthorized', 401);
        }

        // Decode base64 credentials (username:password)
        $credentials = base64_decode(substr($authHeader, 6));
        [$username, $password] = explode(':', $credentials, 2);

        // Check against users table
        $user = User::where('email', $username)->first();

        if ($user && Hash::check($password, $user->password)) {
            return response('Credentials accepted', 200);
        }

        return response('Unauthorized', 401);
    }
}
