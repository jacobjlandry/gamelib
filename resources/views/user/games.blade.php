@extends('master')

@section('content')
    <div class="filters-container">
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default">All</button>
            @foreach(Auth::user()->platforms() as $platform)
                <button type="button" class="btn btn-default">{{ $platform->platform()->name }}</button>
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