<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Zones_machine extends Model
{
    //
    protected $table = 'zones_machine';
    protected $guarded = [];


    public function ssh_run_command($commands){
        try {
            Log::info("Initializing SSH connection to {$this->username}@{$this->ip} on port {$this->port}");

            $ssh_con = new \App\Components\Common\SSH($this->ip, $this->username);
            $ssh_con->set_port($this->port);
            exec("cp /var/www/html/app/Components/Scripts/SSH/testkey /tmp && chmod 0600 /tmp/testkey");
            $newline_add = "\n\n";
            // $ssh_con->set_auth_key($this->ssh_key . $newline_add, $this->id);
            $ssh_con->set_auth_key_file("/tmp/testkey");

            if (is_array($commands)) {
                foreach ($commands as $command) {
                    Log::info("Executing SSH command: $command");
                    $ssh_con->exec($command);
                }
            } else {
                Log::info("Executing single SSH command: $commands");
                $ssh_con->exec($commands);
            }

            Log::info("SSH command(s) executed successfully on {$this->ip}");
            return $ssh_con;

        } catch (\Exception $e) {
            Log::error("SSH command execution failed on {$this->ip}: " . $e->getMessage());
            return false;
        }
    }



    public function ssh_send_file($filepath, $target_fold){
        try {
            Log::info("Initiating file transfer to {$this->username}@{$this->ip}:$target_fold from $filepath");

            $ssh_con = new \App\Components\Common\SSH($this->ip, $this->username);
            $ssh_con->set_port($this->port);
            exec("cp /var/www/html/app/Components/Scripts/SSH/testkey /tmp && chmod 0600 /tmp/testkey");
            $newline_add = "\n\n";
            $ssh_con->set_auth_key_file("/tmp/testkey");
            $ssh_con->put($filepath, $target_fold);

            Log::info("File sent successfully to {$this->ip}:$target_fold");
            return $ssh_con;

        } catch (\Exception $e) {
            Log::error("SSH file transfer failed to {$this->ip}: " . $e->getMessage());
            return false;
        }
    }

}
