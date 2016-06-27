@extends('master')

@section('content')
    <div class="list-container">
        <div class="list">
            @foreach($games as $game)
                <div class="item">
                    <a href="/basic/games/{{ $game->bomb_id }}">
                        <div class="image">
                            @if($game->image)
                                <img src="{{ $game->image }}" class="img-thumbnail" />
                            @else
                                <i class="fa fa-chain-broken fa-4x"></i>
                            @endif
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