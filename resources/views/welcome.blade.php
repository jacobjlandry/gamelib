@extends('master')

@section('content')
<div class="container welcome">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome, {{ Auth::user()->name }}!</div>
                <div class="panel-body graph-panel">
                    <div id="platformChart"></div>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">My Games</div>
                <div class="panel-body">
                    <div id="gamesCount" class="user-stat"><div class="number"><a href="user/games">{{ $games->count() }}</a></div> <div class="descriptor">Total Games</div></div>
                    <div id="platformCount" class="user-stat"><div class="number"><a href="user/platforms">{{ $platforms->count() }}</a></div> <div class="descriptor">Platforms</div></div>
                    <div id="uniqueGames" class="user-stat"><div class="number">{{ $games->unique('game_id')->count()  }}</div><div class="descriptor">Unique Games</div></div>
                    <div id="gamesPlayed" class="user-stat"><div class="number"><a href="/user/games?played=1">{{ $games->where('played', '1')->count() }}</a>/<a href="/user/games?played=0">{{ $games->where('played', '0')->count() }}</a></div><div class="descriptor">Played/Unplayed</div></div>
                    <div id="gamesBeat" class="user-stat"><div class="number"><a href="/user/games?beat=1">{{ $games->where('beat', '1')->count() }}</a>/<a href="/user/games/?beat=0">{{ $games->where('beat', '0')->count() }}</a></div> <div class="descriptor">Beat/Unbeat</div></div>
                </div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">My Ratings</div>
                <div class="panel-body">
                    <div id="gamesRated" class="user-stat"><div class="number"><a href="/user/games?rated=1">{{ $games->filter(function($value, $key) {
                        return $value->rating > 0;
                    })->count() }}</a>/<a href="/user/games?rated=0">{{ $games->where('rating', '0')->count() }}</a></div> <div class="descriptor">Rated/Unrated</div></div>
                    <div id="avgRating" class="user-stat"><div class="number">{{ round($games->filter(function($value, $key) {
                        return $value->rating > 0;
                    })->average('rating'), 1) }}</div><div class="descriptor">Average Rating</div></div>
                    <div id="favoriteGame" class="user-stat">
                        <div class="detail">
                            @if($games->where('rating', $games->max('rating'))->count())
                                <div class="list">
                                    <div class="item">
                                        <a href="/basic/games/{{ $games->where('rating', $games->max('rating'))->first()->game()->bomb_id }}">
                                            <div class="image">
                                                <div class="img">
                                                    @if($games->where('rating', $games->max('rating'))->first()->game()->image)
                                                        <img src="{{ $games->where('rating', $games->max('rating'))->first()->game()->image }}" width="125" />
                                                    @else
                                                        <i class="fa fa-chain-broken fa-4x"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="title">
                                                {{ $games->where('rating', $games->max('rating'))->first()->game()->name }}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="descriptor">
                            Favorite Game
                        </div>
                    </div>
                    <div id="leastFavoriteGame" class="user-stat">
                        <div class="detail">
                            @if($games->where('rating', $games->min('rating'))->count())
                                <div class="list">
                                    <div class="item">
                                        <a href="/basic/games/{{ $games->where('rating', $games->min('rating'))->first()->game()->bomb_id }}">
                                            <div class="image">
                                                <div class="img">
                                                    @if($games->where('rating', $games->min('rating'))->first()->game()->image)
                                                        <img src="{{ $games->filter(function($item, $key) {
                                                            return $item->rating > 0;
                                                        })->sortBy('rating')->first()->game()->image }}" width="125" />
                                                    @else
                                                        <i class="fa fa-chain-broken fa-4x"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="title">
                                                {{ $games->filter(function($item, $key) {
                                                    return $item->rating > 0;
                                                })->sortBy('rating')->first()->game()->name }}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="descriptor">
                            Least Favorite Game
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">Wishlist</div>
                <div class="panel-body">
                    Coming Soon...
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var platforms = Morris.Donut({
        element: 'platformChart',
        data: [
            @foreach($gamesByPlatform as $platformId => $games)
                { label: "{{ $platformId }}", value: {{ $games->count() }}},
            @endforeach
        ],
        colors: ['#1976D2', '#388E3C', '#F57C00', '#F44336', '#FBC02D', '#512DA8', '#C2185B', '#0288D1', '#AFB428', '#E64A19', '#7B1FA2'],
        formatter: function(y, data) {
            return y + ' Games';
        }
    });
</script>
@endsection
