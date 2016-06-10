<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    public $fillable = array('bomb_id');

    public function games()
    {
        return $this->belongsToMany('App\Game')->get();
    }
}
