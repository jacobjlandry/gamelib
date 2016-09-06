<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Game extends Model
{
    public $fillable = array('bomb_id');

    public function platforms()
    {
        return \App\GamePlatform::where('game_id', $this->bomb_id)->get();
    }

    public function owned()
    {
        $userGame = $this->hasMany('App\UserGame', 'game_id', 'bomb_id')->where('user_id', Auth::user()->id)->first();
        if(is_object($userGame)) {
            return $userGame->own;
        }
        else {
            return false;
        }
    }

    public function userGameInfo($platformId)
    {
        return $this->hasMany('App\UserGame', 'game_id', 'bomb_id')->where('platform_id', $platformId)->where('user_id', Auth::user()->id)->first();
    }
}
