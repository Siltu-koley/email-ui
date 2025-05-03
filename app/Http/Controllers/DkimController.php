<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DkimService;
use App\Models\Domain;
use App\Models\DkimKey;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;


class DkimController extends Controller
{
    protected $dkim;

    public function __construct(DkimService $dkim)
    {
        $this->dkim = $dkim;
    }

    public function generate(Request $request)
    {
        $domain_id = $request->post("domainid");
        $domain_data = Domain::where("id", $domain_id)
                            ->where("status", 1)->first();
        if($domain_data){
            $domain_name = $domain_data->domain_name;
            $selector = "default";
            $data = $this->dkim->generateDkim($domain_name, $selector);
            // dd($data);
            $dkim = DkimKey::updateOrCreate(
                ['domain_id' => $domain_id], // search condition
                [
                    'domain'      => $data['domain'],
                    'selector'    => $data['selector'],
                    'txt_name'    => $data['txt_name'],
                    'txt_value'   => $data['txt_value'],
                    'private_key' => $data['private_key'],
                    'public_key'  => $data['public_key'],
                ]
            );
            return response()->json([
                'status' => true,
                'message' => "DKIM created successfully",
                'dkim' => $dkim,
            ]);
        }
        else{
            return response()->json(array("status"=>false, "meaasge"=>"Domain not found"));
        }
    }

    public function getDkim(Request $request) {
        Log::info('DKIM Request Received', $request->all());
        // Expected fields from ZoneMTA
        // $from = $request->input('from');
        // $user = $request->input('user');
        // $origin = $request->input('origin');
        // $transtype = $request->input('transtype');
        $domain = $request->input('fromdomain');

        // Extract domain from MAIL FROM address
        // $domain = substr(strrchr($from, "@"), 1);

        // Find the DKIM key from the DB for this domain
        $dkimKey = DkimKey::where('domain', $domain)->first();

        if (!$dkimKey) {
            return response()->json([]); // No DKIM config — nothing will be signed
        }

        return response()->json([
            'dkim' => [
                'keys' => [
                    'domainName'  => $dkimKey->domain,
                    'keySelector' => $dkimKey->selector,
                    'privateKey'  => $dkimKey->private_key
                ]
            ]
        ]);
    }
}
