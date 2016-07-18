<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlatform extends Model
{
    public $fillable = array('user_id', 'platform_id', 'own');
    public $table = 'user_platform';

    public function platform()
    {
        return $this->hasOne('App\Platform', 'bomb_id', 'platform_id')->first();
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->first();
    }
}
