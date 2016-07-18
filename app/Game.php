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
        return $this->hasMany('App\UserGame', 'game_id', 'bomb_id')->where('user_id', Auth::user()->id)->first()->own;
    }

    public function userGameInfo()
    {
        return $this->hasMany('App\UserGame', 'game_id', 'bomb_id')->where('user_id', Auth::user()->id)->first();
    }
}
