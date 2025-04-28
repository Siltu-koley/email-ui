<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZonemtaMailIp extends Model
{
    protected $table = 'zonemta_mail_ip';
    protected $fillable = [
        'user_id',
        'useremail_id',
        'zone',
        'pool',
        'ip',
        'status',
    ];
}
