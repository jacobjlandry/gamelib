<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Return whether or not this user owns a product
     *
     * @param $resource
     * @param $id
     * @return bool
     */
    public function has($resource, $id)
    {
        switch($resource) {
            default:
            case 'games':
                return \App\UserGame::where('user_id', $this->id)->where('game_id', $id)->first();
                break;

            case 'platforms':
                return \App\UserPlatform::where('user_id', $this->id)->where('platform_id', $id)->first();
                break;
        }
    }

    /**
     * Return whether or not a user has rated a product
     *
     * @param $resource
     * @param $id
     * @return string
     */
    public function littleRating($resource, $id, $number = 1)
    {
        switch($resource) {
            case 'games':
                $userGames = \App\UserGame::where('user_id', $this->id)->where('game_id', $id)->first();
                if(is_object($userGames) && $userGames->rating >= $number) {
                    return "fa-star";
                }
                break;

            case 'platforms':
                $userPlatforms = \App\UserPlatform::where('user_id', $this->id)->where('platform_id', $id)->first();
                if(is_object($userPlatforms) && $userPlatforms->rating >= $number) {
                    return "fa-star";
                }
                break;
        }

        return "fa-star-o";
    }

    /**
     * Get the user's 5-star-rating-system rating for product
     *
     * @param $resource
     * @param $id
     * @return bool
     */
    public function rating($resource, $id)
    {
        $userGames = \App\UserGame::where('user_id', $this->id)->where('game_id', $id)->first();

        if(is_object($userGames)) {
            return $userGames->rating;
        }

        return false;
    }

    /**
     * Rate a product
     *
     * @param $resource
     * @param $id
     * @param $rating
     */
    public function rate($resource, $id, $rating)
    {
        switch($resource) {
            case 'games':
                $userGames = \App\UserGame::where('user_id', $this->id)->where('game_id', $id)->get();
                foreach($userGames as $userGame) {
                    $userGame->rating = $rating;
                    $userGame->save();
                }
                break;

            case 'platforms':
                $userPlatforms = \App\UserPlatform::where('user_id', $this->id)->where('platform_id', $id)->get();
                foreach($userPlatforms as $userPlatform) {
                    $userPlatform->rating = $rating;
                    $userPlatform->save();
                }
                break;
        }
    }

    /**
     * list of games user owns
     * @param $platformId
     */
    public function games($platformId = null)
    {
        if($platformId) {
            return \App\UserGame::where('user_id', $this->id)->where('platform_id', $platformId)->groupBy('game_id')->get();
        }

        return \App\UserGame::select(DB::raw('max(user_id) as user_id, max(game_id) as game_id, max(playing) as playing, max(played) as played, max(beat) as beat, max(rating) as rating, max(own) as own'))
            ->where('user_id', $this->id)
            ->groupBy('game_id')
            ->get();
    }

    /**
     * List of platforms user has games for
     * @return mixed
     */
    public function gamePlatforms()
    {
        return \App\UserGame::select('platform_id', DB::raw('count(*) as games'))->groupBy('platform_id')->get();
    }

    /**
     * list of platforms user owns
     */
    public function platforms()
    {
        return \App\UserPlatform::where('user_id', $this->id)->get();
    }

    /**
     * Return whether a game/platform combo is owned
     *
     * @param $gameId
     * @return mixed
     */
    public function owned($gameId, $platformId)
    {
        return \App\UserGame::where('user_id', $this->id)->where('game_id', $gameId)->where('platform_id', $platformId)->count();
    }

    /**
     * Claim Ownership of a Product
     *
     * @param $resource
     * @param $id
     */
    public function claim($resource, $id, $platformId)
    {
        switch($resource) {
            case 'games':
                $game = \App\Game::where('bomb_id', $id)->first();
                if(!is_object($game)) {
                    $gameInfo = \App\GiantBomb::getItem('game', $id)->results;
                    $game = new \App\Game();
                    $game->bomb_id = $id;
                    $game->name = $gameInfo->name;
                    $game->image = isset($gameInfo->image) ? $gameInfo->image->super_url : null;
                    $game->bomb_url = $gameInfo->api_detail_url;
                    $game->detail_url = $gameInfo->site_detail_url;
                    $game->save();

                    if(isset($gameInfo->platforms) && is_array($gameInfo->platforms)) {
                        foreach($gameInfo->platforms as $platformInfo) {
                            $platform = \App\Platform::where('bomb_id', $platformInfo->id)->first();
                            if(!is_object($platform)) {
                                $platformInfo = \App\GiantBomb::getItem('platform', $platformInfo->id)->results;
                                $platform = new \App\Platform();
                                $platform->bomb_id = $platformInfo->id;
                                $platform->name = $platformInfo->name;
                                $platform->image = $platformInfo->image->super_url;
                                $platform->bomb_url = $platformInfo->api_detail_url;
                                $platform->detail_url = $platformInfo->site_detail_url;
                                $platform->save();

                                $map = new \App\GamePlatform(['game_id' => $game->bomb_id, 'platform_id' => $platform->bomb_id]);
                                $map->save();
                            }
                        }
                    }
                }
                $otherPlatformMap = \App\UserGame::where('user_id', $this->id)->where('game_id', $game->bomb_id)->withTrashed()->first();
                $map = \App\UserGame::where('user_id', $this->id)->where('game_id', $game->bomb_id)->where('platform_id', $platformId)->withTrashed()->first();
                if(!is_object($map)) {
                    $map = new \App\UserGame(['user_id' => $this->id, 'game_id' => $game->bomb_id, 'platform_id' => $platformId, 'own' => 1]);
                }
                else if($map->trashed()) {
                    $map->restore();
                }
                if(is_object($otherPlatformMap) && $otherPlatformMap->rating > 0) {
                    $map->rating = $otherPlatformMap->rating;
                }
                $map->save();
                if(!$this->has('platforms', $platformId)) {
                    $platformMap = new \App\UserPlatform(['user_id' => $this->id, 'platform_id' => $platformId, 'own' => 1]);
                    $platformMap->save();
                }
                break;
            case 'platforms':
                $map = new \App\UserPlatform(['user_id' => $this->id, 'platform_id' => $id, 'own' => 1]);
                $map->save();
                break;
        }

        return $map->id;
    }

    /**
     * Remove Ownership of a Product
     *
     * @param $resource
     * @param $id
     */
    public function toss($resource, $id, $platformId)
    {
        if($resource == "games") {
            $userGame = UserGame::where('platform_id', $platformId)->where('user_id', $this->id)->where('game_id', $id)->first();
            $userGame->delete();
        }
        else {

        }
    }

    public function playing($platformId, $gameId, $value)
    {
        $game = UserGame::where('user_id', $this->id)->where("game_id", $gameId)->where('platform_id', $platformId)->first();
        if($value == "true" || $value == 1) {
            $game->playing = 1;
        }
        else {
            $game->playing = 0;
        }
        $game->save();
    }

    public function played($platformId, $gameId, $value)
    {
        $game = UserGame::where('user_id', $this->id)->where("game_id", $gameId)->where('platform_id', $platformId)->first();
        if($value == "true" || $value == 1) {
            $game->played = 1;
        }
        else {
            $game->played = 0;
        }
        $game->save();
    }

    public function beat($platformId, $gameId, $value)
    {
        $game = UserGame::where('user_id', $this->id)->where("game_id", $gameId)->where('platform_id', $platformId)->first();
        if($value == "true" || $value == 1) {
            $game->beat = 1;
        }
        else {
            $game->beat = 0;
        }
        $game->save();
    }
}
