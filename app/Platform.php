<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Platform extends Model
{
    public $fillable = array('bomb_id');

    public function games()
    {
        return \App\GamePlatform::where('platform_id', $this->bomb_id)->get();
    }

    public function owned()
    {
        $userPlatform = $this->hasMany('App\UserPlatform', 'platform_id', 'bomb_id')->where('user_id', Auth::user()->id)->first();
        if(is_object($userPlatform)) {
            return $userPlatform->own;
        }
        else {
            return false;
        }
    }
}
