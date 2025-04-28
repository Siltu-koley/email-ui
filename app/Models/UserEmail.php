<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    protected $table = 'user_emails';
    protected $fillable = [
        'userid',
        'wildduck_userid',
        'email',
        'main'
    ];
}
