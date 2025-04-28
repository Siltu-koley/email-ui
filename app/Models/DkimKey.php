<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DkimKey extends Model
{
    protected $fillable = [
        'domain_id', 'domain', 'selector', 'txt_name', 'txt_value', 'private_key', 'public_key'
    ];
}
