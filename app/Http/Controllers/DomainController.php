<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Domain;
use App\Models\DkimKey;
use App\Models\UserEmail;
use App\Models\WildduckAccesstoken;
use App\Models\WildDuckUser;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function domains(Request $request) {
        // dd(Auth::user());
        $domains = Domain::where('status',1)->get();
        return view('domains',compact('domains'));
    }
    public function storeDomain(Request $request) {    
        $add_domain = new domain;
        $add_domain->domain_name = $request->post('domain_name');
        $add_domain->maximum_user = $request->post('max_user', 10);
        $add_domain->maximum_alias = $request->post('max_alias', 0);
        $add_domain->comment = $request->post('comment');
        $add_domain->created_by = 1;
        $add_domain->save();
        return(array("success"=>true));
    }

    public function verfy_domain(Request $request) {
        $domain_id = $request->route('domain_id');
        $domain = Domain::where("id", $domain_id)
                            ->where("status", 1)->first();
        $dkim_details = DkimKey::where("domain_id",$domain_id)->first();
        return view('verifydomain', compact("domain","dkim_details"));
    }

    public function createMail(Request $request) {
        $domain_id = $request->route('domain_id');
        $domain = Domain::where("id", $domain_id)
                            ->where("status", 1)->first();
        $user_detail = Auth::User();
        $wildduck_user = WildDuckUser::where('user_id', $user_detail->id)->first();
        return view('createmail', compact("domain","wildduck_user"));
    }
    public function emaillist(Request $request) {
        $domain_id = $request->route('domain_id');
        $domains = Domain::where('id',$domain_id)->first();

        // $emails = UserEmail::where('domain_id',$domains->id)->get();
        // $wd_user = WildDuckUser::where('userid',$emails->userid)->get();

        $emails = DB::table('user_emails')
                ->join('wild_duck_users', 'user_emails.userid', '=', 'wild_duck_users.user_id')
                ->where('user_emails.domain_id', $domains->id)
                ->select('user_emails.*', 'wild_duck_users.username','wild_duck_users.password')
                ->get();
        return view('emaillist',compact('domains','emails'));
    }

    public function storeMail(Request $request) {
        $content = $request->post();

        $hashedPassword = bcrypt($request->input('password'));
        $email = $content['emailstring']."@".$content['emaildomain'];
        $password = $content['password'];
        // Create the new user
        $user = User::create([
            'name' => $email,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
        $createwildduck_user = $this->createwildduck_user($email,$password);
        $result = json_decode($createwildduck_user, true);
        if(isset($result['success']) && $result['success']){
            $add_user = new WildDuckUser();
            $add_user->user_id = Auth::user()->id;
            $add_user->username = $email;
            $add_user->password = $request->input('password');
            $add_user->wildduck_userid = $result['id'];
            $add_user->default_mail = $email;
            $add_user->save();

            $add_useremail = new UserEmail();
            $add_useremail->userid = Auth::user()->id;
            $add_useremail->wildduck_userid = $result['id'];
            $add_useremail->domain_id = $content['domain_id'];
            $add_useremail->email = $email;
            $add_useremail->main = true;
            $add_useremail->allowWildcard = false;
            $add_useremail->save();
            return(array("success"=>true));
        }
        return(array("success"=>false, "message"=>"Something went wrong"));
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
}
