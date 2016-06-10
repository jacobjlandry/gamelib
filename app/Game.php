<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $fillable = array('bomb_id');

    public function platforms()
    {
        return $this->belongsToMany('App\Platform')->get();
    }
}
