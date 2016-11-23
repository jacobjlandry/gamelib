@extends('master')

@section('content')
<div class="container welcome">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome {{ Auth::user()->name }}</div>
                <div class="panel-body">
                    <div id="platformChart"></div>
                    <div id="gamesCount"><div class="number"><a href="user/games">{{ $games->count() }}</a></div> <div class="descriptor">Total Games</div></div>
                    <div id="platformCount"><div class="number"><a href="user/platforms">{{ $platforms->count() }}</a></div> <div class="descriptor">Platforms</div></div>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">My Games</div>
                <div class="panel-body">
                    <div id="uniqueGames"><div class="number">{{ $games->unique('game_id')->count()  }}</div><div class="descriptor">Unique Games</div></div>
                    <div id="gamesPlayed"><div class="number"><a href="/user/games?played=1">{{ $games->where('played', 1)->count() }}</a>/<a href="/user/games?played=0">{{ $games->where('played', 0)->count() }}</a></div><div class="descriptor">Played/Unplayed</div></div>
                    <div id="gamesBeat"><div class="number">{{ $games->where('beat', 1)->count() }}/{{ $games->where('beat', 0)->count() }}</div> <div class="descriptor">Beat/Unbeat</div></div>
                </div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">Ratings</div>
                <div class="panel-body">
                    <div id="gamesRated"><div class="number"><a href="user/games">{{ $games->filter(function($value, $key) {
                        return $value->rating > 0;
                    })->count() }}/{{ $games->where('rating', 0)->count() }}</a></div> <div class="descriptor">Rated/Unrated</div></div>
                    <div id="avgRating"><div class="number">{{ $games->filter(function($value, $key) {
                        return $value->rating > 0;
                    })->average('rating') }}</div><div class="descriptor">Average Rating</div></div>
                    <div id="favoriteGame"><div class="detail">{{ $games->where('rating', $games->max('rating'))->first()->game()->name }}</div><div class="descriptor">Favorite Game</div></div>
                    <div id="leastFavoriteGame"><div class="detail">{{ $games->where('rating', $games->min('rating'))->first()->game()->name }}</div><div class="descriptor">Least Favorite Game</div></div>
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
