<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailcount extends Model
{
    protected $fillable = [
        'domain_id',
        'email_id',
        'sent'
    ];
}
