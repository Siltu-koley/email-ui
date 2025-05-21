<?php

namespace App\Http\Controllers;

use App\Models\Routingip;
use App\Models\Zones_machine;
use Illuminate\Http\Request;
use \Ovh\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class Ovhcontroller extends Controller
{
    //
    public function index()
    {
        return view('ovh.index');
    }

    public function check_attached_ip($ip_id){
        try {
            $applicationKey = env('OVH_APPLICATION_KEY');
            $applicationSecret = env('OVH_APPLICATION_SECRET');
            $endpoint = env('OVH_ENDPOINT');
            $consumerKey = env('OVH_CONSUMER_KEY');
            $ovh = new Api($applicationKey, $applicationSecret, $endpoint, $consumerKey);
            $serviceName = "314d083a02054b4086b46232ac65ac0b";

            Log::info("Checking attachment for IP: $ip_id");
            $attached_ip = $ovh->get("/cloud/project/$serviceName/ip/failover/$ip_id");

            Log::info("Attached IP response", $attached_ip);
            return $attached_ip['status'] == 'ok' ? $attached_ip : false;
        } catch (\Exception $e) {
            Log::error("Error checking attached IP: " . $e->getMessage());
            return false;
        }
    }


    public function check_until_attached_ip($ip_id){
        $check_each_ip = $this->check_attached_ip($ip_id);
        if ($check_each_ip != false) {
            sleep(5);
            Log::info("Retrying check for IP: $ip_id");
            return $this->check_until_attached_ip($ip_id);
        }
        return $check_each_ip;
    }


    public function check_ip_with_vm($ip, $vm, $ovh){
        try {
            $instance_id = $vm->instance_id;
            $serviceName = "314d083a02054b4086b46232ac65ac0b";

            $all_additional_ips = $ovh->get("/cloud/project/$serviceName/ip/failover");
            Log::info("Checking if IP $ip is routed to VM $instance_id");

            foreach ($all_additional_ips as $address_data) {
                if ($address_data['ip'] == $ip) {
                    if ($address_data['routedTo'] == $instance_id) {
                        Log::info("IP $ip is already attached to VM $instance_id");
                        return true;
                    } else {
                        Log::info("IP $ip is routed to another instance: " . $address_data['routedTo']);
                        return $address_data['id'];
                    }
                }
            }

            Log::warning("IP $ip not found in list");
            return false;
        } catch (\Exception $e) {
            Log::error("Error checking IP routing: " . $e->getMessage());
            return false;
        }
    }


    public function add_address_to_server($server, $address){
        try {
            Log::info("Adding IP $address to server " . $server->id);

            $server->ssh_send_file("/var/www/html/app/Components/Scripts/add_dummy_net_safe.sh", "/home/ubuntu");
            $server->ssh_run_command("chmod +x /home/ubuntu/add_dummy_net_safe.sh");
            $server->ssh_run_command("sudo /home/ubuntu/add_dummy_net_safe.sh $address/32");

            $add_address_to_zone = new Routingip();
            $add_address_to_zone->zone_id = $server->zone_id;
            $add_address_to_zone->ip = $address;
            $add_address_to_zone->status = 1;
            $add_address_to_zone->save();

            Log::info("IP $address successfully added and saved.");
            return response()->json(['status' => 'success', 'message' => 'IP address attached successfully']);
        } catch (\Exception $e) {
            Log::error("Failed to add IP $address to server: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to attach IP to server.']);
        }
    }



    public function attached_additional_ip_to_vm(Request $request){
        try {
            $zone_server = $request->get('zone_server') ?: 1;
            $attached_ip = $request->get('attached_ip') ?: 1;

            Log::info("Attaching additional IP $attached_ip to zone $zone_server");

            $Zone_machine = Zones_machine::where('zone_id', $zone_server)->first();
            if (!$Zone_machine) {
                Log::warning("No machine found with zone_id: $zone_server");
                return response()->json(['status' => 'error', 'message' => "Machine with zone id $zone_server not found"]);
            }

            $ovh = new Api(
                env('OVH_APPLICATION_KEY'),
                env('OVH_APPLICATION_SECRET'),
                env('OVH_ENDPOINT'),
                env('OVH_CONSUMER_KEY')
            );
            $serviceName = "314d083a02054b4086b46232ac65ac0b";

            $check_ip = $this->check_ip_with_vm($attached_ip, $Zone_machine, $ovh);
            if ($check_ip === true) {
                return $this->add_address_to_server($Zone_machine, $attached_ip);
            } elseif ($check_ip !== false) {
                Log::info("Attaching failover IP $attached_ip to instance");
                $response = $ovh->post("/cloud/project/$serviceName/ip/failover/$attached_ip/attach", [
                    'instanceId' => $Zone_machine->instance_id,
                ]);

                Log::info("Attach response: ", $response);

                if ($response['status'] === 'ok') {
                    return $this->add_address_to_server($Zone_machine, $attached_ip);
                } else {
                    Log::warning("Attach response not ok, checking fallback method...");
                    $check = $this->check_attached_ip($attached_ip);
                    return $check !== false
                        ? $this->add_address_to_server($Zone_machine, $attached_ip)
                        : response()->json(['status' => 'error', 'message' => 'IP address not attached.']);
                }
            } else {
                Log::warning("IP address $attached_ip not found or not attached to any VM.");
                return response()->json(['status' => 'error', 'message' => 'IP address not found or not attached to any VM.']);
            }
        } catch (\Exception $e) {
            Log::error("Exception while attaching additional IP: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error']);
        }
    }


    public function get_all_additonal_ips(Request $request){
        try {
            $applicationKey = env('OVH_APPLICATION_KEY');
            $applicationSecret = env('OVH_APPLICATION_SECRET');
            $endpoint = env('OVH_ENDPOINT');
            $consumerKey = env('OVH_CONSUMER_KEY');
            $ovh = new Api($applicationKey, $applicationSecret, $endpoint, $consumerKey);

            $serviceName = "314d083a02054b4086b46232ac65ac0b";
            $vm_id = "51902c67-ad6f-4c64-9195-ec6c44c1f1f6";
            $all_routing_ips = Routingip::where('zone_id', 1)->pluck('ip')->toArray();

            $all_additional_ips = $ovh->get("/cloud/project/$serviceName/ip/failover");
            Log::info("Fetched additional IPs from OVH", ['count' => count($all_additional_ips)]);

            $unmapped_ips = [];
            foreach ($all_additional_ips as $address_data) {
                if (!in_array($address_data['ip'], $all_routing_ips)) {
                    $unmapped_ips[] = $address_data;
                }
            }

            return response()->json([
                'status' => 'success',
                'vm_id' => $vm_id,
                'data' => $unmapped_ips
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching additional IPs: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Could not fetch additional IPs.'
            ]);
        }
    }

    public function check_ssh(Request $request){
        $server=Zones_machine::where('id',1)->first();
        if($server){
            $uuid = Str::uuid();
            $tmp_folder = "/tmp/$uuid";
            mkdir($tmp_folder);
            exec("cp /var/www/html/app/Components/Scripts/add_dummy_net_safe.sh $tmp_folder");
            exec(" chmod 0777 $tmp_folder/add_dummy_net_safe.sh");
            $data=$server->ssh_send_file("$tmp_folder/add_dummy_net_safe.sh", "/home/ubuntu");
            return response()->json(['status'=>true,'message'=>$data]);
        }else{
            return response()->json(['status'=>true,'message'=>'No machine found']);
        }
    }

}
