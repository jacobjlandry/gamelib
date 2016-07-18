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
                        <div class="controls rating" style="display: inline; padding-left: 15px;">
                            @for($x = 1; $x <= 5; $x++)
                                <a href="#"><i id="star{{ $x }}" class="fa {{ Auth::user()->littleRating($resource, $item->id) }}"></i></a>
                            @endfor
                        </div>
                        <div id="{{ $resource }}{{ $item->id }}" class="controls" style="float: right;">
                            <a href="#"><i class="claim fa fa-plus-circle fa-pull-right @if(Auth::user()->has($resource, $item->id))full @endif" id="{{ $item->id }}"></i></a>
                            <a href="#"><i class="toss fa fa-minus-circle fa-pull-right @if(Auth::user()->has($resource, $item->id))full @endif" id="{{ $item->id }}"></i></a>
                        </div>
                    </th>
                </tr>
            </thead>
            <tr>
                <td>Detail URL</td>
                <td><a href="{{ $item->detail_url }}">{{ $item->detail_url }}</a></td>
            </tr>
            <tr>
                @if($resource == "games")
                    <td>Platforms</td>
                    <td>
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            @foreach($item->platforms() as $platform)
                                <label class="btn btn-default @if(Auth::user()->owned($item->bomb_id, $platform->platform()->bomb_id)) active @endif">
                                    <input type="checkbox" autocomplete="off" checked> {{ $platform->platform()->name }}
                                </label>
                            @endforeach
                        </div>
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
            @if($item->owned())
                <tr>
                    <td>Playing</td>
                    <td>
                        <div class="btn-group btn-group-justified" role="group" aria-label="Platforms">
                            <a href="#" class="btn btn-default @if($item->userGameInfo()->playing) active @endif" role="button">Yes</a>
                            <a href="#" class="btn btn-default @if(!$item->userGameInfo()->playing) active @endif" role="button">No</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Played</td>
                    <td>
                        <div class="btn-group btn-group-justified" role="group" aria-label="Platforms">
                            <a href="#" class="btn btn-default @if($item->userGameInfo()->played) active @endif" role="button">Yes</a>
                            <a href="#" class="btn btn-default @if(!$item->userGameInfo()->played) active @endif" role="button">No</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Beat</td>
                    <td>
                        <div class="btn-group btn-group-justified" role="group" aria-label="Platforms">
                            <a href="#" class="btn btn-default @if($item->userGameInfo()->beat) active @endif" role="button">Yes</a>
                            <a href="#" class="btn btn-default @if(!$item->userGameInfo()->beat) active @endif" role="button">No</a>
                        </div>
                    </td>
                </tr>
            @endif
        </table>
    </div>
@endsection