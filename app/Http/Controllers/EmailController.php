<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Filter;
use App\Models\Routingip;
use App\Models\UserEmail;
use App\Models\WildduckAccesstoken;
use App\Models\WildDuckUser;
use App\Models\Zone;
use Faker\Core\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    public function mailbox(Request $request) {
        $user_emailid = $request->route('email_id');
        $mail_details = UserEmail::where("id",$user_emailid)->first();
        // $inbox_msg = $this->getInbox($mail_details->wildduck_userid, $mail_details->inbox_id);
        // $inbox_result = json_decode($inbox_msg, true);
        $inbox_result = '{
    "success": true,
    "total": 1,
    "page": 1,
    "previousCursor": false,
    "nextCursor": false,
    "specialUse": null,
    "results": [
        {
            "id": 1,
            "mailbox": "680e6e5b502eb440c31ae4a7",
            "thread": "680f0fd3740f900012b4a3eb",
            "from": {
                "address": "siltu.koley@stagingstack.com",
                "name": ""
            },
            "to": [
                {
                    "address": "siltu11@stagingstack.com",
                    "name": ""
                }
            ],
            "cc": [],
            "bcc": [],
            "messageId": "<74a52305-0886-cfc5-2dcb-6a92a81c352c@stagingstack.com>",
            "subject": "hello email",
            "date": "2025-04-28T05:19:10.000Z",
            "idate": "2025-04-28T05:19:15.533Z",
            "intro": "test email from zoneMTA 2",
            "attachments": false,
            "attachmentsList": [],
            "size": 879,
            "seen": false,
            "deleted": false,
            "flagged": false,
            "draft": false,
            "answered": false,
            "forwarded": false,
            "references": [],
            "contentType": {
                "value": "text/plain",
                "params": {
                    "charset": "utf-8"
                }
            }
        }
    ]
}';
$inbox_result = json_decode($inbox_result, true);
// dd($inbox_result['success']);
        $data = array();
        if(isset($inbox_result['success']) && $inbox_result['success']){
            $data = $inbox_result['results'];
        }
        return view('mailbox', compact('data'));
    }
    public function getInbox($wildduck_userid,$mailbox_id) {
        $access_token = WildduckAccesstoken::where("wildduck_userid",$wildduck_userid)->first();
        $url = env('WILDDUCK_URL')."/users/$wildduck_userid/mailboxes/$mailbox_id/messages";
        $header = array(
            'Accept: application/json',
            'X-Access-Token: '.$access_token->access_token
        );
        $inbox_msg = curlGet($url,$header);
        return $inbox_msg;
    }

    public function sendmail(Request $request) {
        $mail_details = UserEmail::where("id",$request->input('sender'))->first();
        $data = array(
            "from" => $mail_details->email,
            "to" => $request->input('to_mail'),
            "subject" => $request->input('subject'),
            "text" => $request->input('message'),
        );
        $url = env('ZONEMTA_URL')."/send";
        $header = array(
            'Accept: application/json'
        );

        $from = $mail_details->email;
        $to = $request->input('to_mail');
        $subject = $request->input('subject');
        $text = $request->input('message');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "from": "'.$from.'",
            "to": "'.$to.'",
            "subject": "'.$subject.'",
            "text": "'.$text.'"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // $sendmail = curlPost($url,$data,$header);
        $result = json_decode($response, true);
        if(isset($result['id'])){
            return array("success"=>true);
        }else{
            return array("success"=>false);
        }
    }

    public function getIp(Request $request) {
        $email = $request->route('email');
        $get_mail_ip = UserEmail::where('email', $email)->first();
        $filters = Filter::where('status',1)->get();
        foreach($filters as $filter){
            $filter_arr[] = $filter->filter;
        }
        if($get_mail_ip){
            $ips = $get_mail_ip->routing_ips;
            if($ips != '' && $ips != '[]' && $ips != null){
                $ip_arr = json_decode($ips, true);
            }else{
                $domains = Domain::where('id',$get_mail_ip->domain_id)->first();
                $domain_ips = $domains->default_ips;
                if($domain_ips != '' && $domain_ips != '[]' && $domain_ips != null){
                    $ip_arr = json_decode($domain_ips, true);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => "No ip found"
                    ]);
                }
            }
            $randomIp = $ip_arr[array_rand($ip_arr)];
            return response()->json([
            'success' => true,
            'ip' => $randomIp,
            'filters' => [json_encode($filter_arr,JSON_UNESCAPED_SLASHES)],
            'header_filter' => $filter_arr
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Email not found"
                ]);
        }


    }

    public function emailconfig(Request $request) {
        $email_id = $request->route('email_id');
        $emails = DB::table('user_emails')
                ->join('wild_duck_users', 'user_emails.wildduck_userid', '=', 'wild_duck_users.wildduck_userid')
                ->where('user_emails.id', $email_id)
                ->select('user_emails.*','user_emails.email as email_id','user_emails.id as user_emails_id', 'wild_duck_users.username','wild_duck_users.password')
                ->first();
        $domain = Domain::where('id', $emails->domain_id)->first();
        $ips = Routingip::select('ip')
                        ->where('zone_id', $domain->zone)
                        ->where('status', 1)->get();
        $ip_array = array();
        foreach ($ips as $ip) {
            $ip_array[] = $ip->ip;
        }
        return view('emailconfig',compact('emails','ip_array'));
    }

    public function add_routing_ip(Request $request) {
        $emails_id = $request->post('emails_id');
        $routing_ip = $request->post('routing_ip');
        $getip = UserEmail::find($emails_id);
        if($getip){
        $new_ips = array();
        if($getip->routing_ips != ''){
            $ip_arr = json_decode($getip->routing_ips, true);
            if(in_array($routing_ip, $ip_arr))
                {
                    return ['status' => false,'message' => 'IP already exists'];
                }
            array_push($ip_arr,$routing_ip);
            $getip->routing_ips = json_encode($ip_arr);
        }else{
            array_push($new_ips,$routing_ip);
            $getip->routing_ips = json_encode($new_ips);
        }


        $getip->save();
        return ['status' => true,'message' => 'Route ip updated'];
    }else{
        return ['status' => false,'message' => 'Email not found'];
    }
    }

    public function remove_routing_ip(Request $request) {
        $emails_id = $request->post('emails_id');
        $routing_ip = $request->post('routing_ip');
        $getip = UserEmail::find($emails_id);
        if($getip){
        if($getip->routing_ips != ''){
            $ip_arr = json_decode($getip->routing_ips, true);
            if(!in_array($routing_ip, $ip_arr))
                {
                    return ['status' => false,'message' => 'IP already removed'];
                }
            $ips = array_diff($ip_arr, [$routing_ip]);
            $ips = array_values($ips);
            $getip->routing_ips = json_encode($ips);
            $getip->save();
            return ['status' => true,'message' => 'Route ip removed'];
        }else{
            return ['status' => false,'message' => 'No ip found'];
        }
    }else{
        return ['status' => false,'message' => 'Email not found'];
    }
    }
    public function ZonesIp(Request $request) {
        $zones = Zone::where('status', 1)->get();
        if($zones->isEmpty()){
            $zonedata=[];
        }else{
            foreach($zones as $zone){
                $ips = Routingip::where('zone_id', $zone->id)
                            ->where('status', 1)->get();
                $ip_array = array();
                foreach ($ips as $ip) {
                    $ip_array[] = $ip->ip;
                }
                $data['id'] = $zone->id;
                $data['zone'] = $zone->zone;
                $data['ips'] = $ip_array;
            }
            $zonedata[] = $data;
        }


        return view('zonesip',compact('zonedata'));
    }
    public function update_smtp_pass(Request $request) {
        $email_id = $request->post('email_id');
        $newpassword = $request->post('newpassword');
        $hashedPassword = bcrypt($newpassword);
        $useremail = UserEmail::find($email_id);
        $wld_user = WildDuckUser::where('wildduck_userid', $useremail->wildduck_userid)->first();
        $update_wdpass = $this->updatewdpass($wld_user->password,$newpassword,$useremail->wildduck_userid);
        $result = json_decode($update_wdpass, true);
        if(isset($result['success']) && $result['success']){
            $wld_user->password = $newpassword;
            $wld_user->hashed_password = $hashedPassword;
            $wld_user->save();
            return ['status' => true,'message' => 'SMTP password updated'];
        }else{
            return ['status' => false,'message' => 'Something went wrong'];
        }
    }

    public function updatewdpass($password,$newpassword,$wildduck_userid) {
        $data = array(
            "existingPassword" => $password,
            "password" => $newpassword
        );

        $url = env('WILDDUCK_URL')."/users/".$wildduck_userid;
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json',
            // 'X-Access-Token: '.$access_token->access_token
        );
        $updatepass = curlPost($url,$data,$header);
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_POSTFIELDS =>json_encode($data),
          CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function addFilter(Request $request) {
        $filterregex = $request->post('filterregex');
        $filter = new Filter;
        $filter->filter = $filterregex;
        $filter->created_by = Auth::user()->id;
        $filter->save();
        return redirect()->back();
    }
    public function filter(Request $request) {
        $filters = Filter::where('status',1)->get();
        return view('filters', compact('filters'));
    }
    public function editFilter(Request $request) {
        $filterregex = $request->post('editfilterregex');
        $regex_id = $request->post('regex_id');
        $filter = Filter::where('id',$regex_id)->first();
        $filter->filter = $filterregex;
        $filter->created_by = Auth::user()->id;
        $filter->save();
        return redirect()->back();
    }
    public function deleteFilter(Request $request) {
        $filter_id = $request->route('filter_id');
        $filter = Filter::find($filter_id);

        if ($filter) {
            $filter->delete();
            return ['status'=>true, 'message' => 'Filter deleted successfully'];
        }

        return ['status'=>false, 'message' => 'Filter not found'];
    }

    public function deleteMail(Request $request) {
        $email_id = $request->route('email_id');

        $useremail = UserEmail::find($email_id);

        if ($useremail) {
            $wd_user = WildDuckUser::where('wildduck_userid', $useremail->wildduck_userid)->first();
            if($wd_user){
                $deletewildduck_user = $this->deleteWduser($useremail->wildduck_userid);
                $result = json_decode($deletewildduck_user, true);
                if(isset($result['success']) && $result['success']){
                    $useremail->delete();
                    $wd_user->delete();
                    return ['status'=>true, 'message' => 'Email deleted successfully'];
                }else{
                    return ['status'=>false, 'message' => 'something wrong on wildduck'];
                }

            }
        }

        return ['status'=>false, 'message' => 'Email not found'];
    }

    public function deleteWduser($wildduck_userid) {

        $url = env('WILDDUCK_URL')."/users/".$wildduck_userid;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            // 'X-Access-Token: <X-Access-Token>'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
