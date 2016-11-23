<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.login', ['resource' => 'user']);
    }

    public function home()
    {
        if(Auth::user()) {
            $games = Auth::user()->games();
            $platforms = array();
            foreach($games->unique('platform_id') as $platform) {
                $platforms[] = $platform->platform_id;
            }

            $gamesByPlatform = array();
            foreach($platforms as $platform) {
                $gamesByPlatform[\App\Platform::where('bomb_id', $platform)->first()->name] = $games->where('platform_id', $platform);
            }

            return view('welcome', ['user' => Auth::user(), 'resource' => 'user', 'games' => $games, 'platforms' => collect($platforms), 'gamesByPlatform' => collect($gamesByPlatform)]);
        }
        else {
            return $this->login();
        }
    }

    public function register()
    {
        return view('auth.register', ['resource' => 'user']);
    }

    public function reset()
    {
        return view('auth.passwords.reset', ['resource' => 'user', 'token' => csrf_token()]);
    }

    public function games(Request $request, $platformId = null)
    {
        $games = Auth::user()->games($platformId);

        // gather played/unplayed if needed
        // @TODO put this in session for when user goes or clicks on a platform?
        if($request->input('played') == 1) {
            $games = $games->where('played', 1);
        }
        else if($request->input('played') == '0') {
            $games = $games->where('played', 0);
        }

        $list = array();
        foreach($games as $owned) {
            $game = \App\Game::where('bomb_id', $owned->game_id)->first();
            $game->platform = \App\Platform::where('bomb_id', $owned->platform_id)->first()->name;
            $list[] = $game;
        }

        $list = collect($list);

        return view('user.games', ['user' => Auth::user(), 'resource' => 'user', 'games' => $list, 'platformId' => $platformId]);
    }

    public function platforms()
    {
        $list = Auth::user()->platforms();
        return view('user.platforms', ['user' => Auth::user(), 'resource' => 'user', 'platforms' => $list]);
    }
}
