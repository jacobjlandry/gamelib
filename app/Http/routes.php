<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', 'UserController@home');
Route::get('/home', 'HomeController@index');

Route::get('/password/reset', 'UserController@reset');

Route::get('list/{resource}/{page?}', function($resource, $page = 1) {
    $list = \App\GiantBomb::getList($resource, $page);
    
    return view('list', ['list' => $list->results,
        'max_results' => $list->number_of_total_results,
        'page_results' => $list->number_of_page_results,
        'page_limit' => $list->limit,
        'page' => $page,
        'resource' => $resource]);
});

Route::get('basic/{resource}/{id}', function($resource, $id) {
    switch($resource) {
        case 'platforms':
            $item = \App\Platform::firstOrNew(['bomb_id' => $id]);
            break;
        case 'games':
            $item = \App\Game::firstOrNew(['bomb_id' => $id]);
            if(!$item->exists) {
                // Create Game
                $details = \App\GiantBomb::getItem("game", $id);
                $item->name = $details->results->name;
                $item->image = $details->results->image->super_url;
                $item->bomb_url = $details->results->site_detail_url;
                $item->detail_url = $details->results->api_detail_url;
                $item->save();

                foreach($details->results->platforms as $platformInfo) {
                    $platform = \App\Platform::firstOrNew(['bomb_id' => $platformInfo->id]);
                    // Create Platform
                    if(!$platform->exists) {
                        $platformDetails = \App\GiantBomb::getItem("platform", $platformInfo->id);
                        $platform->name = $platformDetails->results->name;
                        $platform->image = $platformDetails->results->image->super_url;
                        $platform->bomb_url = $platformDetails->results->site_detail_url;
                        $platform->detail_url = $platformDetails->results->api_detail_url;
                        $platform->save();
                    }

                    // Map Game to Platform
                    DB::table('game_platform')->insert(['game_id' => $item->bomb_id, 'platform_id' => $platform->bomb_id,
                        'created_at' => date('Y-m-d H:i:s', time()), 'updated_at' => date('Y-m-d H:i:s', time())]);
                }
            }
            break;
    }

    return view('basic', ['item' => $item, 'resource' => $resource]);
});

Route::get('game/playing/{platformId}/{gameId}/{value}', function($platformId, $gameId, $value) {
    Auth::user()->playing($platformId, $gameId, $value);
});
Route::get('game/played/{platformId}/{gameId}/{value}', function($platformId, $gameId, $value) {
    Auth::user()->played($platformId, $gameId, $value);
});
Route::get('game/beat/{platformId}/{gameId}/{value}', function($platformId, $gameId, $value) {
    Auth::user()->beat($platformId, $gameId, $value);
});

Route::get('/search/{term}', function($term) {
    $list = \App\GiantBomb::search($term);

    return view('list', ['list' => $list->results,
        'max_results' => $list->number_of_total_results,
        'page_results' => $list->number_of_page_results,
        'page_limit' => $list->limit,
        'page' => 1,
        'resource' => "games"]);
});

Route::get('/user/claim/{resource}/{id}/{platformId?}', function($resource, $id, $platformId = null) {
    return Auth::user()->claim($resource, $id, $platformId);
});
Route::get('/user/toss/{resource}/{id}/{platformId?}', function($resource, $id, $platformId = null) {
    return Auth::user()->toss($resource, $id, $platformId);
});

Route::get('/user/rate/{resource}/{id}/{rating}/{platformId?}', function($resource, $id, $rating, $platformId = null) {
    return Auth::user()->rate($resource, $id, $rating, $platformId);
});

Route::get('/user/games/{platformId?}', 'UserController@games');
Route::get('/user/platforms', 'UserController@platforms');

Route::auth();

Route::get('/home', 'HomeController@index');
