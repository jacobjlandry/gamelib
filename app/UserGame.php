<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGame extends Model
{
    public $fillable = array('user_id', 'game_id', 'platform_id', 'own');
    public $table = 'user_game';
}