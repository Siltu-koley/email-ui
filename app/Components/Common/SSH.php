<?php

namespace App\Components\Common;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SSH
{
    protected $host;
    protected $user;
    protected $password = null;
    protected $ssh_key_path = null;
    protected $log_file = null;
    protected $port = 22;
    protected $ensure_run_as_root = false;

    public function __construct($host, $user='root')
    {
        $this->host = $host;
        $this->user = $user;
        exec("ssh-keygen -f \"/root/.ssh/known_hosts\" -R ".$host);
    }

    public function ensure_run_as_root($state=true)
    {
        $this->ensure_run_as_root = $state;
        return $this;
    }

    public function as_root($state=true)
    {
        return $this->ensure_run_as_root($state);
    }

    public function set_log_file($path)
    {
        $this->log_file = $path;
    }

    public function set_password($password)
    {
        $this->password = $password;
    }

    public function set_auth_key_file($path)
    {
        $this->ssh_key_path = $path;
    }

    function process_template($template, array $variables=array(), $varAppend='${', $varPrepend='}')
    {
        //___debug($variables);
        foreach($variables as $key => $value) {
            $var = $varAppend . $key . $varPrepend;
            if(stripos($template, $var)!== false) {
                $template = str_replace($var, $value, $template);
            }
        }
        return $template;
    }

    public function set_auth_key($key, $server_id)
    {
        // Create a secure and consistent path in the system temp directory
        $key_hash = md5($key);
        $key_path = sys_get_temp_dir() . '/' . $key_hash . '.' . $server_id . '.rsa';

        if (!file_exists($key_path)) {
            // If $key is a file path, read its content
            if (file_exists($key)) {
                $key = file_get_contents($key, false, null, 0, filesize($key));
            }

            // Normalize line endings to Unix-style
            $key = str_replace(["\r\n", "\r"], "\n", $key);

            // Validate key format (basic sanity check)
            if (!str_starts_with($key, '-----BEGIN') || !str_contains($key, 'PRIVATE KEY')) {
                throw new \Exception("Invalid SSH private key format.");
            }

            // Save the key to the temp file securely
            file_put_contents($key_path, $key);

            // Set strict file permissions
            chmod($key_path, 0600);

            // Log path only, never the key content
            Log::info("SSH private key saved to: " . $key_path);
        }

        $this->ssh_key_path = $key_path;
    }

    public function set_port($port)
    {
        $this->port = $port;
    }

    protected function exec_auth_key($cmd)
    {
        exec('chmod 0600 '.$this->ssh_key_path);
        // exec('chown sail:root ' . escapeshellarg($this->ssh_key_path));
        $commend = "/usr/bin/ssh -p ".$this->port." -o StrictHostKeyChecking=no -i ".$this->ssh_key_path." " . $this->user . "@" . $this->host . " '( ".$cmd." )' ";

//        ___debug($commend);

        if ($this->log_file != false) {
            return shell_exec($commend ." >> " .$this->log_file);
        }
        return shell_exec($commend);
    }

    protected function exec_auth_password($cmd)
    {
        $commend = "sshpass -p \"".$this->password."\" /usr/bin/ssh -p ".$this->port." -o StrictHostKeyChecking=no " . $this->user . "@" . $this->host . " '( ".$cmd." )' ";
        //___debug($commend);
        if ($this->log_file != false) {
            return shell_exec($commend ." >> " .$this->log_file);
        }
        return shell_exec($commend);
    }

    public function exec($cmd, $print_cmd=false)
    {
        if($this->ensure_run_as_root == true && $this->user != 'root') {
            if(stripos($cmd, 'sudo') !== false) {
                $cmd = 'sudo ' . $cmd;
            } else {
                $cmd = 'sudo ' . str_replace('&&', '&& sudo',$cmd);
            }

        }
        if($print_cmd != false) {
            print_r($cmd);
            exit;
        }
        if($this->ssh_key_path != null) {
            return $this->exec_auth_key($cmd);
        }
        return $this->exec_auth_password($cmd);
    }

    protected function copy_to_server_auth_key($source, $target)
    {
        //___debug(file_get_contents($source));
        exec('chmod 0600 '.$this->ssh_key_path);
        // exec('chown sail:root ' . escapeshellarg($this->ssh_key_path));
        $commend = "/usr/bin/scp -P ".$this->port." -r -o StrictHostKeyChecking=no -i ".$this->ssh_key_path." ".$source." " . $this->user . "@" . $this->host . ":".$target;
//        ___debug($commend);
        if ($this->log_file != false) {
            return exec($commend ." >> " .$this->log_file);
        }
        return exec($commend);
    }

    protected function copy_to_server_auth_password($source, $target)
    {
        $commend = "sshpass -p \"".$this->password."\" /usr/bin/scp -P ".$this->port." -r  -o StrictHostKeyChecking=no ".$source." " . $this->user . "@" . $this->host . ":".$target;
        //___debug($commend);
        if ($this->log_file != false) {
            return exec($commend ." >> " .$this->log_file);
        }
        return exec($commend);
    }

    public function put($source, $target, $variables=null)
    {
        if($variables==null) {
            if ($this->ssh_key_path != null) {
                return $this->copy_to_server_auth_key($source, $target);
            }
            return $this->copy_to_server_auth_password($source, $target);
        } else {
            $temp_complied_script_path = '/tmp/' . md5($source) . '.' . date('U') . '.out' ;
            file_put_contents($temp_complied_script_path, $this->process_template(file_get_contents($source), $variables, '$[', ']'));
            return $this->put($temp_complied_script_path, $target);
        }
    }

    public function run($script, $params=null)
    {
        if(is_array($params)) {
            return $this->run_template($script, $params);
        } else {
            $tmp_script_path = '/tmp/' . md5(date('U')) . md5(Str::uuid()->toString()) . '.script';
            $this->put($script, $tmp_script_path);
            if($this->ensure_run_as_root == true && $this->user != 'root') {
                $return = $this->exec('chmod +x ' . $tmp_script_path . ' && sudo ' . $tmp_script_path);
            } else {
                $return = $this->exec('chmod +x ' . $tmp_script_path . ' && ' . $tmp_script_path);
            }
            $this->exec('rm -rf ' . $tmp_script_path);
            return $return;
        }
    }

    public function run_template($template_path, $variables)
    {
        if(!file_exists($template_path)) {
            // \Model\Error::trigger(new \Exception($template_path . ' do not exists'));
        }
        $temp_complied_script_path = '/tmp/' . md5($template_path) . '.' . date('U') . '.sh' ;
        //___debug($this->process_template(file_get_contents($template_path), $variables, '$[', ']'));
        file_put_contents($temp_complied_script_path, $this->process_template(file_get_contents($template_path), $variables, '$[', ']'));
        $result = $this->run($temp_complied_script_path);
        unlink($temp_complied_script_path);
        return $result;
    }

    public function connection_test($log,$cmd="pwd") {
        // Ensure SSH key permissions are set correctly
        exec('chmod 0600 ' . escapeshellarg($this->ssh_key_path));
        // exec('chown sail:root ' . escapeshellarg($this->ssh_key_path));
        // Build the SSH command
        $command = "/usr/bin/ssh -p " . escapeshellarg($this->port) .
                   " -o StrictHostKeyChecking=no -i " . escapeshellarg($this->ssh_key_path) .
                   " " . escapeshellarg($this->user) . "@" . escapeshellarg($this->host) .
                   " '( " . escapeshellarg($cmd) . " )'";

        // Retry mechanism
        $maxAttempts = 10;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            // echo "Attempting SSH connection (Attempt " . ($attempt + 1) . ")..." . PHP_EOL;
            $log->log("Attempting SSH connection (Attempt " . ($attempt + 1) . ")...");
            if ($this->log_file != false) {
                // Execute SSH command and log the output if a log file is provided
                $output = shell_exec($command . " >> " . escapeshellarg($this->log_file) . " 2>&1");
            } else {
                // Execute SSH command without logging
                $output = shell_exec($command . " 2>&1");
            }

            // Check if the command was successful
            if ($output !== null && str_contains($output,"/home"."/".$this->user)) {
                $log->log("SSH connection successful data with output ".$output." on attempt " . ($attempt + 1) );
                return ["status"=>true,"data"=>$output];
            } else {
                // echo "SSH connection failed on attempt " . ($attempt + 1) . PHP_EOL;
                $log->log("SSH connection failed on attempt " . ($attempt + 1));

            }

            // Increment attempt counter and wait 30 seconds before retrying
            $attempt++;
            if ($attempt < $maxAttempts) {
                sleep(30); // Delay 30 seconds before retrying
            }
        }

        // If all attempts failed, return false
        // echo "All SSH connection attempts failed." . PHP_EOL;
        // Log::info("All SSH connection attempts failed");
        $log->log("All SSH connection attempts failed");
        // throw new Exception( "All SSH connection attempts failed." );
        return false;
    }

}
