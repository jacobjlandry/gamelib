<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;

class GiantBomb extends Model
{
    public static function getList($resource, $page)
    {
        ini_set("user_agent","Games Library");
        $params = array_merge(array('api_key' => env('BOMB_KEY'),'format' => 'json'), array('offset' => ($page-1)*100));
        return json_decode(file_get_contents(env('BOMB_URL') . $resource . '?' . http_build_query($params)));
    }

    public static function search($term, $page)
    {
        ini_set("user_agent","Games Library");
        $params = array_merge(array('api_key' => env('BOMB_KEY'),'format' => 'json'), array('query' => $term, 'resources' => 'game', 'page' => $page));
        return json_decode(file_get_contents(env('BOMB_URL') . "search?" . http_build_query($params)));
    }

    public static function getItem($resource, $id)
    {
        ini_set("user_agent","Games Library");
        return json_decode(file_get_contents(env('BOMB_URL') . $resource . "/" . $id . '?' . http_build_query(array('api_key' => env('BOMB_KEY'),'format' => 'json'))));
    }
}
