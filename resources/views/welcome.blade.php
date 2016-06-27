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
                    <div id="platformCount"><div class="number">{{ $platforms->count() }}</div> <div class="descriptor">Platforms</div></div>
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
