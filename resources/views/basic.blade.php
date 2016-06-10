@extends('master')

@section('content')
    <div class="item-detail">
        <img src="{{ $item->image }}" height="400" />
        <br />
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="2">
                        {{ $item->name }}
                        <div class="controls" style="float: right;">
                            <a href="#"><i class="fa fa-plus-circle fa-pull-right @if(Auth::user()->has($resource, $item->id))full @endif"></i></a>
                            <a href="#"><i class="fa fa-pull-right {{ Auth::user()->littleRating($resource, $item->id) }}"></i></a>
                            <a href="#"><i class="fa fa-minus-circle fa-pull-right @if(Auth::user()->has($resource, $item->id))full @endif"></i></a>
                        </div>
                    </th>
                </tr>
            </thead>
            <tr>
                <td>Detail URL</td>
                <td><a href="{{ $item->bomb_url }}">{{ $item->bomb_url }}</a></td>
            </tr>
            <tr>
                @if($resource == "games")
                    <td>Platforms</td>
                    <td>
                        @foreach($item->platforms() as $platform)
                            {{ $platform->name }}<br />
                        @endforeach
                    </td>
                @elseif($resource == "platforms")
                    <td>Games</td>
                    <td>
                        @foreach($item->games() as $game)
                            {{ $game->name }}<br />
                        @endforeach
                    </td>
                @endif
            </tr>
        </table>
    </div>
@endsection