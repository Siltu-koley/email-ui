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
        $emails = UserEmail::where('domain_id',$domains->id)->get();
        return view('emaillist',compact('domains','emails'));
    }

    public function storeMail(Request $request) {
        $content = $request->post();
        $data = array(
            "address" => $content['emailstring']."@".$content['emaildomain'],
            "main" => false,
            "allowWildcard" => false
        );
        $user_id = Auth::user()->id;
        $access_token = WildduckAccesstoken::where("user_id",$user_id)->first();
        $wildduckuser = WildDuckUser::where("user_id",$user_id)->first();
        $wildduck_userid = $content['wildduck_userid'];
        $url = env('WILDDUCK_URL')."/users/".$wildduck_userid."/addresses";
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Access-Token: '.$access_token->access_token
        );
        $create_mail = curlPost($url,$data,$header);
        $create_result = json_decode($create_mail, true);
        if($create_result['success']){
            $add_mail = new UserEmail;
            $add_mail->userid = $user_id;
            $add_mail->wildduck_userid = $wildduckuser->wildduck_userid;
            $add_mail->domain_id = $content['domain_id'];
            $add_mail->email = $content['emailstring']."@".$content['emaildomain'];
            $add_mail->main = isset($content['emailstring']) ? 1 : 0;
            $add_mail->save();
            return(array("success"=>true));
        }
        return(array("success"=>false, "message"=>"Something went wrong"));
    }
}
