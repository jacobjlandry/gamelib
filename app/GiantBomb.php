<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;

class GiantBomb extends Model
{
    const baseURL = 'http://www.giantbomb.com/api/';
    const baseURI = array('api_key' => '743dedd31e502fa9e4fc17531182b7bab3802ab7','format' => 'json');

    public static function list($resource, $page)
    {
        ini_set("user_agent","Games Library");
        $params = array_merge(self::baseURI, array('offset' => ($page-1)*100));
        return json_decode(file_get_contents(self::baseURL . $resource . '?' . http_build_query($params)));
    }

    public static function search($term)
    {
        ini_set("user_agent","Games Library");
        //Log::debug("GiantBomb API Query", array(self::baseURL . "search" . self::baseURI . "&query=" . $term));
        $params = array_merge(self::baseURI, array('query' => $term, 'resources' => 'game'));
        return json_decode(file_get_contents(self::baseURL . "search?" . http_build_query($params)));
    }

    public static function item($resource, $id)
    {
        ini_set("user_agent","Games Library");
        //Log::debug('GiantBomb API Call', array(self::baseURL, $resource, $id, self::baseURI));
        return json_decode(file_get_contents(self::baseURL . $resource . "/" . $id . '?' . http_build_query(self::baseURI)));
    }
}
