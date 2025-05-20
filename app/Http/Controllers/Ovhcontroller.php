<?php

namespace App\Http\Controllers;

use App\Models\Routingip;
use App\Models\Zones_machine;
use Illuminate\Http\Request;
use \Ovh\Api;


class Ovhcontroller extends Controller
{
    //
    public function index()
    {
        return view('ovh.index');
    }

    public function check_attached_ip($ip_id){
        $applicationKey = env('OVH_APPLICATION_KEY');
        $applicationSecret = env('OVH_APPLICATION_SECRET');
        $endpoint = env('OVH_ENDPOINT');
        $consumerKey = env('OVH_CONSUMER_KEY');
        $ovh = new Api($applicationKey,
            $applicationSecret,
            $endpoint,
            $consumerKey);
        $serviceName = "314d083a02054b4086b46232ac65ac0b";

        $attached_ip = $ovh->get("/cloud/project/$serviceName/ip/failover/$ip_id");
        if($attached_ip['status'] == 'ok'){
            return $attached_ip;
        }else{
            return false;
        }
    }

    public function check_until_attached_ip($ip_id){
        $check_each_ip= $this->check_attached_ip($ip_id);
        if($check_each_ip != false){
            sleep(5);
            $this->check_until_attached_ip($ip_id);
        }
        return $check_each_ip;
    }

    public function check_ip_with_vm($ip,$vm,$ovh){
        $instance_id = $vm->instance_id;
        $serviceName = "314d083a02054b4086b46232ac65ac0b";
        $all_additonal_ips=$ovh->get("/cloud/project/$serviceName/ip/failover");
        if(count($all_additonal_ips) > 0){
            foreach ($all_additonal_ips as $address_data){
                if($address_data['ip'] == $ip){
                    if($address_data['routedTo'] == $instance_id){
                        return true;
                    }else{
                        return $address_data['id'];
                    }
                }else{
                    return false;
                }
            }
        }
    }

    public function add_address_to_server($server,$address){

        $server->ssh_send_file("app/Components/Scripts/add_dummy_net_safe.sh","/home/ubuntu");
        $server->ssh_run_command("chmod +x /home/ubuntu/add_dummy_net_safe.sh");
        $server->ssh_run_command("sudo /home/ubuntu/add_dummy_net_safe.sh $address/32");

        $add_address_to_zone = new Routingip();
        $add_address_to_zone->zone_id = $server->zone_id;
        $add_address_to_zone->ip = $address;
        $add_address_to_zone->status = 1;
        $add_address_to_zone->save();

        return response()->json(['status' => 'success', 'message' => 'IP address attached successfully']);
    }


    public function attached_additional_ip_to_vm(Request $request){
        $zone_server = $request->get('zone_server')? $request->get('zone_server') : 1;
        $attached_ip = $request->get('attached_ip')? $request->get('attached_ip') : 1;
        // $server_id = "51902c67-ad6f-4c64-9195-ec6c44c1f1f6";
        $Zone_machine = Zones_machine::where('zone_id', $zone_server)->first();
        if($Zone_machine){

            // Api credentials can be retrieved from the urls specified in the "Supported endpoints" section below.
            $applicationKey = env('OVH_APPLICATION_KEY');
            $applicationSecret = env('OVH_APPLICATION_SECRET');
            $endpoint = env('OVH_ENDPOINT');
            $consumerKey = env('OVH_CONSUMER_KEY');
            $ovh = new Api($applicationKey,
                            $applicationSecret,
                            $endpoint,
                            $consumerKey);
            $serviceName = "314d083a02054b4086b46232ac65ac0b";

            $check_ip_with_vm_checks = $this->check_ip_with_vm($attached_ip,$Zone_machine,$ovh);
            if($check_ip_with_vm_checks == true){

                return $this->add_address_to_server($Zone_machine,$attached_ip);

            }elseif($check_ip_with_vm_checks != false){
                $attached_ip_to_vm=$ovh->post("/cloud/project/$serviceName/ip/failover/$attached_ip/attach", array(
                'instanceId' => $Zone_machine->instance_id, // Attach failover ip to instance (type: string)
                ));

                if($attached_ip_to_vm['status'] == 'ok'){
                    return $this->add_address_to_server($Zone_machine,$attached_ip);
                }else{
                    $check_attached_Adress=$this->check_attached_ip($attached_ip);
                    if($check_attached_Adress != false){
                        return $this->add_address_to_server($Zone_machine,$attached_ip);
                    }else{
                        return response()->json(['status' => 'error', 'message' => 'Something went wrong! IP address not attached. Please check the IP address or try again.']);
                    }
                }

            }else{
                return response()->json(['status' => 'error', 'message' => 'IP address not found or not attached to any VM.']);

            }


        }else{
            return response()->json(['status' => 'error', 'message' => 'Machine with zone id '.$zone_server.' not found']);
        }
    }

    public function get_all_additonal_ips(Request $request){
        // Api credentials can be retrieved from the urls specified in the "Supported endpoints" section below.
            $applicationKey = env('OVH_APPLICATION_KEY');
            $applicationSecret = env('OVH_APPLICATION_SECRET');
            $endpoint = env('OVH_ENDPOINT');
            $consumerKey = env('OVH_CONSUMER_KEY');
            $ovh = new Api($applicationKey,
                            $applicationSecret,
                            $endpoint,
                            $consumerKey);
            $serviceName = "314d083a02054b4086b46232ac65ac0b";
            $vm_id = "51902c67-ad6f-4c64-9195-ec6c44c1f1f6";
            $all_routing_ips = Routingip::where('zone_id', 1)->pluck('ip')->toArray();
            $all_additional_ips=$ovh->get("/cloud/project/$serviceName/ip/failover");
            // Filter to get only IPs NOT in routing IPs
            $unmapped_ips = [];

            if (count($all_additional_ips) > 0) {
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
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No additional IPs found'
                ]);
            }
    }
}
