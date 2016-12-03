<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGame extends Model
{
    use SoftDeletes;

    public $fillable = array('user_id', 'game_id', 'platform_id', 'own');
    public $table = 'user_game';

    public function game()
    {
        return \App\Game::where('bomb_id', $this->game_id)->first();
    }

    public function platform()
    {
        return $this->hasOne('App\Platform', 'bomb_id', 'platform_id')->first();
    }
}
