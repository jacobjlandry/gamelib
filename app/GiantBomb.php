<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiantBomb extends Model
{
    const baseURL = 'http://www.giantbomb.com/api/';
    const baseURI = '?api_key=743dedd31e502fa9e4fc17531182b7bab3802ab7&format=json';

    public static function list($resource, $page)
    {
        ini_set("user_agent","Games Library");
        return json_decode(file_get_contents(self::baseURL . $resource . self::baseURI . "&offset=" . ($page-1)*100));
    }

    public static function search($term)
    {
        ini_set("user_agent","Games Library");
        return json_decode(file_get_contents(self::baseURL . "search" . self::baseURI . "&query=" . $term));
    }

    public static function item($resource, $id)
    {
        ini_set("user_agent","Games Library");
        return json_decode(file_get_contents(self::baseURL . $resource . "/" . $id . self::baseURI));
    }
}
