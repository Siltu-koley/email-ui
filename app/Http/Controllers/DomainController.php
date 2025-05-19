<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Domain;
use App\Models\DkimKey;
use App\Models\Routingip;
use App\Models\UserEmail;
use App\Models\WildduckAccesstoken;
use App\Models\WildDuckUser;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DkimController;
use App\Models\Mailcount;

use function Laravel\Prompts\text;

class DomainController extends Controller
{
    public function domains(Request $request) {
        // dd(Auth::user());
        $zones = Zone::where('status',1)->get();

        $domains = DB::table('domains')
                ->join('users', 'users.id', '=', 'domains.created_by')
                ->where('domains.status', 1)
                // ->where('domains.created_by', Auth::user()->id)
                ->select('domains.*', 'users.name')
                ->get();
        $domaindata = array();
        foreach($domains as $domain){
            $data['emailcount'] = UserEmail::where('domain_id', $domain->id)->count();
            $data['mailsent'] = Mailcount::where('domain_id', $domain->id)->sum('sent');
            $data['id'] = $domain->id;
            $data['domain_name'] = $domain->domain_name;
            $data['zone'] = $domain->zone;
            $data['default_ips'] = $domain->default_ips;
            $data['created_at'] = $domain->created_at;
            $data['name'] = $domain->name;
            $domaindata[] = $data;
        }

        return view('domains',compact('domaindata','zones'));
    }
    public function storeDomain(Request $request) {   
        $default_ips = $request->post('selected_ips');
        $add_domain = new domain;
        $add_domain->domain_name = $request->post('domain_name');
        $add_domain->created_by = Auth::user()->id;
        $add_domain->zone = $request->post('zone');
        $add_domain->default_ips = json_encode($default_ips);
        $add_domain->save();

        $domain_id = $add_domain->id;
        return(array("success"=>true, 'domain_id' => $domain_id));
    }

    public function updateRoute(Request $request) {
        $domain_id = $request->post('domain_id');
        $default_ips = $request->post('selected_ips');
        $domain = Domain::find($domain_id);
        if ($domain) {
            $domain->default_ips = $default_ips;
            $domain->save();
            return ['success' => true,'message' => 'Route updated'];
        } else {
            return response()->json(['message' => 'Domain not found'], 404);
        }
    }

    public function verfy_domain(Request $request) {
        $domain_id = $request->route('domain_id');
        $domain = Domain::where("id", $domain_id)
                            ->where("status", 1)->first();
        $dkim_details = DkimKey::where("domain_id",$domain_id)->first();
        $ips = Routingip::where('zone_id', $domain->zone)->get();
        // $spf_string = "v=spf1 ip4:15.204.28.113 ip4:192.168.1.10 ip4:192.168.1.20 -all"
        $spf_string = "v=spf1 ";
        foreach ($ips as $ip) {
            $spf_string .= "ip4:" . $ip->ip . " ";
        }
        $final_spf = trim($spf_string) . " -all";
        return view('verifydomain', compact("domain","dkim_details", "final_spf"));
    }

    public function createMail(Request $request) {
        $domain_id = $request->route('domain_id');
        $domain = Domain::where("id", $domain_id)
                            ->where("status", 1)->first();
        $user_detail = Auth::User();
        // $wildduck_user = WildDuckUser::where('user_id', $user_detail->id)->first();
        return view('createmail', compact("domain"));
    }
    public function emaillist(Request $request) {
        $domain_id = $request->route('domain_id');
        $domains = Domain::where('id',$domain_id)->first();

        $emails = DB::table('user_emails')
                ->join('wild_duck_users', 'user_emails.wildduck_userid', '=', 'wild_duck_users.wildduck_userid')
                ->where('user_emails.domain_id', $domains->id)
                ->select('user_emails.*', 'wild_duck_users.username','wild_duck_users.password')
                ->get();

        return view('emaillist',compact('domains','emails'));
    }

    public function storeMail(Request $request) {
        $content = $request->post();
        $password = $this->generatePassword();

        $hashedPassword = bcrypt($password);
        $email = $content['emailstring']."@".$content['emaildomain'];
        

        $createwildduck_user = $this->createwildduck_user($email,$password);
        $result = json_decode($createwildduck_user, true);
        if(isset($result['success']) && $result['success']){
            $add_user = new WildDuckUser();
            $add_user->username = $email;
            $add_user->password = $password;
            $add_user->hashed_password = $hashedPassword;
            $add_user->wildduck_userid = $result['id'];
            $add_user->default_mail = $email;
            $add_user->save();

            $add_useremail = new UserEmail();
            $add_useremail->wildduck_userid = $result['id'];
            $add_useremail->domain_id = $content['domain_id'];
            $add_useremail->email = $email;
            $add_useremail->main = true;
            $add_useremail->allowWildcard = false;
            $add_useremail->created_by = Auth::user()->id;
            $add_useremail->save();
            return(array("success"=>true));
        }
        return(array("success"=>false, "message"=>"Something went wrong"));
    }

    public function generatePassword($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letters (upper + lower) and digits
        $password = '';
    
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
    
        return $password;
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

    public function getzoneip(Request $request) {
        $zone_id = $request->route('zone_id');
        $ips = Routingip::select('ip')
                        ->where('zone_id', $zone_id)
                        ->where('status', 1)->get();
        $ip_array = array();
        foreach ($ips as $ip) {
            $ip_array[] = $ip->ip;
        }
        return $ip_array;
    }

    public function get_domain(Request $request) {
        $domain_id = $request->route('domain_id');
        $domain_details = Domain::where('id', $domain_id)->first();
        $ips = Routingip::select('ip')
                        ->where('zone_id', $domain_details->zone)
                        ->where('status', 1)->get();
        $ip_array = array();
        foreach ($ips as $ip) {
            $ip_array[] = $ip->ip;
        }
        return response()->json([
            'status' => 'success',
            'domain_details' => $domain_details,
            'zone_ips' => $ip_array
        ]);
    }

    public function deleteDomain(Request $request) {
        $domain_id = $request->route('domain_id');
        $domain = Domain::find($domain_id);
        
        if ($domain) {
            $dkim = DkimKey::where('domain_id',$domain_id)->first();
            $domain->delete();
            if($dkim){
                $dkim->delete();
            }
            return ['status'=>true, 'message' => 'Domain deleted successfully'];
        }
        
        return ['status'=>false, 'message' => 'Domain not found'];
    }
}
