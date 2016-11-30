<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GamePlatform extends Model
{
    public $fillable = array('game_id', 'platform_id');
    public $table = 'game_platform';

    public function platform()
    {
        return $this->hasOne('App\Platform', 'bomb_id', 'platform_id')->first();
    }

    public function game()
    {
        return $this->hasOne('App\Game', 'bomb_id', 'game_id')->first();
    }
}
