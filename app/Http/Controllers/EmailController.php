<?php

namespace App\Http\Controllers;

use App\Models\UserEmail;
use App\Models\WildduckAccesstoken;
use Illuminate\Http\Request;

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
}
