<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WildduckAccesstoken extends Model
{
    protected $fillable = ['user_id', 'wildduck_userid', 'access_token'];
}
