<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zones_machine extends Model
{
    //
    protected $table = 'zones_machine';
    protected $guarded = [];


    public function ssh_run_command($commands){
        $ssh_con=new \App\Components\Common\SSH($this->ip,$this->username);
        $ssh_con->set_port($this->port);
        // Log::info("ssh_run_command ".$this->username."@".$this->ip." -p ".$this->port."with ssh key
        // and command want to run => ".$commands);
        // Log::info("ssh_key =>"."\n".$this->ssh_key."\n end ssh building");
        $newline_add="

        ";
        $ssh_con->set_auth_key($this->ssh_key."".$newline_add,$this->id);

        if (is_array($commands)){
            foreach ($commands as $command){
                $ssh_con->exec($command);
            }
        }else{
            $ssh_con->exec($commands);
        }
        return $ssh_con;
    }


    public function  ssh_send_file($filepath,$target_fold){
        $ssh_con=new \App\Components\Common\SSH($this->ip,$this->username);
        $ssh_con->set_port($this->port);
        $newline_add="

        ";
        $ssh_con->set_auth_key($this->ssh_key."".$newline_add,$this->id);
        $ssh_con->put($filepath,$target_fold);
        return $ssh_con;
    }
}
