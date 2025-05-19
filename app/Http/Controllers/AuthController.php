<?php

namespace App\Http\Controllers;

use App\Models\Mailcount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\WildDuckUser;
use App\Models\WildduckAccesstoken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
            // $wildduck_authenticate = $this->authenticate_to_wildduck($request->input('username'),$request->input('password'));
            // $result = json_decode($wildduck_authenticate, true);
            // if(isset($result['success']) && $result['success']){
            //     $update_access_token = WildduckAccesstoken::updateOrCreate(
            //         ['user_id'=>Auth::user()->id, 'wildduck_userid' => $result['id']],
            //         ['access_token' => $result['token']]
            //     );
            // }

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

        
        // $createwildduck_user = $this->createwildduck_user($request->input('email'),$request->input('password'));
        // $result = json_decode($createwildduck_user, true);
        // if(isset($result['success']) && $result['success']){
        //     $add_user = new WildDuckUser();
        //     $add_user->user_id = $user->id;
        //     $add_user->username = $request->input('email');
        //     $add_user->password = $request->input('password');
        //     $add_user->wildduck_userid = $result['id'];
        //     $add_user->default_mail = $request->input('email');
        //     $add_user->save();
        // }
        return redirect()->route('login');
    }
    public function createwildduck_user($email,$password) {
        $data = array(
            "username" => $email,
            "password" => $password,
            "hashedPassword" => false,
            "allowUnsafe" => true,
            "address" => $email,
            "fromWhitelist" => [$email]
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
        // Log::info('authHeader: ' .$authHeader);

        if (!$authHeader || !str_starts_with($authHeader, 'Basic ')) {
            return response('Unauthorized', 401);
        }

        // Decode base64 credentials (username:password)
        $credentials = base64_decode(substr($authHeader, 6));
        [$username, $password] = explode(':', $credentials, 2);

        //check against useremail table
        $user = WildDuckUser::where('username', $username)->first();

        if($user){
            $expectedBase64 = base64_encode($user->username . ':' . $user->password);
            $expectedAuthHeader = 'Basic ' . $expectedBase64;
            $authHeader = trim($authHeader);
            if(Hash::check($password, $user->hashed_password) || $password === $user->password || $authHeader === $expectedAuthHeader){
                
                return response('Credentials accepted', 200);
            }
        }

        return response('Unauthorized', 401);
    }

    public function update_pass(Request $request) {
        $oldpass = $request->post('oldpass');
        $newpass = $request->post('newpass');

        $user = Auth::user();

        if (!Hash::check($oldpass, $user->password)) {
            return ['success'=> false, 'message' => 'The current password is incorrect.'];
        }

        $user->password = Hash::make($newpass);
        $user->save();

        return ['success'=> true, 'message' => 'Password updated successfully.'];
    }

    public function smtpAuth(Request $request) {
        // Log::info('request received');
        $username = $request->input('username');
        $password = $request->input('password');
        $user = WildDuckUser::where('username', $username)->first();

        if($user){
            if(Hash::check($password, $user->hashed_password) || $password === $user->password){
                $usermail = UserEmail::where('email', $username)->first();
                $mailcount = Mailcount::where('email_id', $usermail->id)->first();
                if ($mailcount) {
                    $mailcount->increment('sent');
                } else {
                    Mailcount::create([
                        'email_id' => $usermail->id,
                        'domain_id' => $usermail->domain_id,
                        'sent' => 1,
                    ]);
                }
                return response()->json([
                    'status' => true
                ]);
            }
        }
        return response()->json([
            'status' => false
        ]);
    }

    public function checkmongo(Request $request) {
        $getwdusers = WildDuckUser::where('status', 1)->get();
        foreach($getwdusers as $wdusers){
            $checkwd = $this->checkwd($wdusers->username,$wdusers->password);
            $result = json_decode($checkwd, true);
            if(isset($result['error']) && $result['code'] === 'AuthFailed'){
                $createuser = $this->createwildduck_user($wdusers->username,$wdusers->password);
                $resultuser = json_decode($createuser, true);
                if(isset($resultuser['success']) && $resultuser['success']){
                    $wd_user =  WildDuckUser::find($wdusers->id);
                    $wd_user->wildduck_userid = $resultuser['id'];
                    $wd_user->save();

                    $wd_useremail = UserEmail::where('email',$wdusers->username)->first();
                    $wd_useremail->wildduck_userid = $resultuser['id'];
                    $wd_useremail->save();
                }
                Log::info('user added for: ' .$wdusers->username);
            }
            
        }
        return(array("success"=>true, "message" => "Users checked and updated"));
    }
    public function checkwd($username,$password) {
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
}
