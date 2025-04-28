<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DkimService;
use App\Models\Domain;
use App\Models\DkimKey;
use Illuminate\Support\Arr;

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
}
