<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WildDuckUser extends Model
{
    protected $table = 'wild_duck_users';
    
    protected $fillable = [
        'username',
        'password',
        'default_mail',
        'status',
    ];
}
