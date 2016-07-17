<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

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
    public function littleRating($resource, $id)
    {
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

    }

    /**
     * list of games user owns
     * @param $platformId
     */
    public function games($platformId = null)
    {
        if($platformId) {
            return \App\UserGame::where('platform_id', $platformId)->get();
        }

        return \App\UserGame::all();
    }

    /**
     * list of platforms user owns
     */
    public function platforms()
    {
        return \App\UserPlatform::all();
    }

    /**
     * Claim Ownership of a Product
     *
     * @param $resource
     * @param $id
     */
    public function claim($resource, $id, $platformId = null)
    {
        switch($resource) {
            case 'games':
                $game = \App\Game::where('bomb_id', $id)->first();
                if(!is_object($game)) {
                    $gameInfo = \App\GiantBomb::item('game', $id)->results;
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
                                $platformInfo = \App\GiantBomb::item('platform', $platformInfo->id)->results;
                                $platform = new \App\Platform();
                                $platform->bomb_id = $platformInfo->id;
                                $platform->name = $platformInfo->name;
                                $platform->image = $platformInfo->image->super_url;
                                $platform->bomb_url = $platformInfo->api_detail_url;
                                $platform->detail_url = $platformInfo->site_detail_url;
                                $platform->save();

                                $map = new \App\GamePlatform(['game_id' => $game->id, 'platform_id' => $platform->id]);
                                $map->save();
                            }
                        }
                    }
                }
                $map = new \App\UserGame(['user_id' => $this->id, 'game_id' => $game->bomb_id, 'platform_id' => $platformId]);
                $map->save();
                if(!$this->has('platforms', $platformId)) {
                    $platformMap = new \App\UserPlatform(['user_id' => $this->id, 'platform_id' => $platformId]);
                    $platformMap->save();
                }
                break;
            case 'platforms':
                $map = new \App\UserPlatform(['user_id' => $this->id, 'platform_id' => $id]);
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
    public function toss($resource, $id)
    {

    }
}
