<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlatform extends Model
{
    public $fillable = array('user_id', 'platform_id');
    public $table = 'user_platform';
}
