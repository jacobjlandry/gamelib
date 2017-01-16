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
            $platforms = \App\UserGame::select('platform_id')->where('user_id', Auth::user()->id)->get()->unique('platform_id');

            $gamesByPlatform = array();
            foreach($platforms as $platform) {
                $gamesByPlatform[\App\Platform::where('bomb_id', $platform->platform_id)->first()->name] = $games->where('platform_id', $platform->platform_id);
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

        // gather playing if needed
        if($request->input('playing') == 1) {
            $games = $games->where('playing', '1');
        }
        else if($request->input('playing') == '0') {
            $games = $games->where('playing', '0');
        }

        // gather played/unplayed if needed
        // @TODO put this in session for when user goes or clicks on a platform?
        if($request->input('played') == 1) {
            $games = $games->where('played', '1');
        }
        else if($request->input('played') == '0') {
            $games = $games->where('played', '0');
        }

        // get beat/unbeat games
        // @TODO put this in session for when user goes or clicks on a platform?
        if($request->input('beat') == 1) {
            $games = $games->where('beat', '1');
        }
        else if($request->input('beat') == '0') {
            $games = $games->where('beat', '0');
        }

        // get rated/unrated games
        // @TODO put this in session for when user goes or clicks on a platform?
        if($request->input('rated') == 1) {
            $games = $games->filter(function($value, $key) {
                return $value->rating > 0;
            });
        }
        else if($request->input('rated') == '0') {
            $games = $games->where('rating', '0');
        }

        $list = array();
        foreach($games as $owned) {
            $list[] = \App\Game::where('bomb_id', $owned->game_id)->first();
        }

        $list = collect($list)->sortBy('name');

        return view('user.games', ['user' => Auth::user(), 'resource' => 'user', 'games' => $list, 'platformId' => $platformId]);
    }

    public function platforms()
    {
        $list = Auth::user()->platforms();
        return view('user.platforms', ['user' => Auth::user(), 'resource' => 'user', 'platforms' => $list]);
    }
}
