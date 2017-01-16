@extends('master')

@section('content')
    <div class="filters-container">
        <div class="user-game-controls" role="group" aria-label="Platforms" style="min-width: {{ (Auth::user()->platforms()->count() + 1) * 75 }}px;">
            <div class="item first"><a href="/user/games" class="@if(!$platformId) active @endif" role="button">All</a></div>
            @foreach(Auth::user()->games()->unique('platform_id') as $game)
                <div class="item middle @if($platformId == $game->platform_id) active @endif" title="{{ $game->platform()->name }}"><a href="/user/games/{{ $game->platform_id }}">{{ $game->platform()->nickname }}</a></div>
            @endforeach
        </div>
    </div>
    <div class="list-container">
        <div class="list">
            @foreach($games as $game)
                <div class="item">
                    <a href="/basic/games/{{ $game->bomb_id }}">
                        <div class="image">
                            <div class="img">
                                @if($game->image)
                                    <img src="{{ $game->image }}" width="125" />
                                @else
                                    <i class="fa fa-chain-broken fa-4x"></i>
                                @endif
                            </div>
                        </div>
                        <div class="title">
                            {{ $game->name }}
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection