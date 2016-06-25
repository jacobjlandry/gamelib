<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GamePlatform extends Model
{
    public $fillable = array('game_id', 'platform_id');
    public $table = 'game_platform';
}
