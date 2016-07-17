@extends('master')

@section('content')
    <div class="filters-container">
        <div class="btn-group btn-group-justified" role="group" aria-label="Platforms">
            <a href="/user/games" class="btn btn-default @if(!$platformId) active @endif" role="button">All</a>
            @foreach(Auth::user()->platforms() as $platform)
                <a href="/user/games/{{ $platform->platform_id }}" class="btn btn-default @if($platformId == $platform->platform_id) active @endif" role="button">{{ $platform->platform()->name }}</a>
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
                            {{ $game->name }} ({{ $game->platform }})
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection