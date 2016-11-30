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
                                <a href="#"><i id="star{{ $x }}" class="fa {{ Auth::user()->littleRating($resource, $item->bomb_id, $x) }} rating-star @if($x <= Auth::user()->rating($resource, $item->bomb_id)) full @endif"></i></a>
                            @endfor
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
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            @foreach($item->platforms() as $platform)
                                <label class="btn btn-default @if(Auth::user()->owned($item->bomb_id, $platform->platform()->bomb_id)) active @endif platform">
                                    <input type="checkbox" autocomplete="off" value="{{ $platform->platform()->bomb_id }}" @if(Auth::user()->owned($item->bomb_id, $platform->platform()->bomb_id)) checked @endif> {{ $platform->platform()->name }}
                                </label>
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
            @if($item->owned() && $resource == "games")
                <tr>
                    <td>Playing</td>
                    <td>
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            @foreach($item->platforms() as $platform)
                                @if(Auth::user()->owned($item->bomb_id, $platform->platform()->bomb_id))
                                    <label class="btn btn-default @if($item->userGameInfo($platform->platform()->bomb_id)->playing) active @endif playing">
                                        <input type="checkbox" autocomplete="off" value="{{ $platform->platform()->bomb_id }}" @if($item->userGameInfo($platform->platform()->bomb_id)->playing) checked @endif> {{ $platform->platform()->name }}
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Played</td>
                    <td>
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            @foreach($item->platforms() as $platform)
                                @if(Auth::user()->owned($item->bomb_id, $platform->platform()->bomb_id))
                                    <label class="btn btn-default @if($item->userGameInfo($platform->platform()->bomb_id)->played) active @endif played">
                                        <input type="checkbox" autocomplete="off" value="{{ $platform->platform()->bomb_id }}" @if($item->userGameInfo($platform->platform()->bomb_id)->played) checked @endif> {{ $platform->platform()->name }}
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Beat</td>
                    <td>
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            @foreach($item->platforms() as $platform)
                                @if(Auth::user()->owned($item->bomb_id, $platform->platform()->bomb_id))
                                    <label class="btn btn-default @if($item->userGameInfo($platform->platform()->bomb_id)->beat) active @endif beat">
                                        <input type="checkbox" autocomplete="off" value="{{ $platform->platform()->bomb_id }}" @if($item->userGameInfo($platform->platform()->bomb_id)->beat) checked @endif> {{ $platform->platform()->name }}
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // update platforms owned
            $('.platform').on('click', function(e) {
                if($(e.target).find('input').is(':checked')) {
                    $.get('/user/toss/games/{{ $item->bomb_id }}/' + $(e.target).find('input').attr('value'));
                }
                else {
                    $.get('/user/claim/games/{{ $item->bomb_id }}/' + $(e.target).find('input').attr('value'));
                }
            });
            // update user-game status
            $('.playing').on('click', function(e) {
                $.get('/game/playing/' + $(e.target).find('input').attr('value') + '/' + {{ $item->bomb_id }} + '/' + !$(e.target).find('input').is(':checked'));
            });
            $('.played').on('click', function(e) {
                $.get('/game/played/' + $(e.target).find('input').attr('value') + '/' + {{ $item->bomb_id }} + '/' + !$(e.target).find('input').is(':checked'));
            });
            $('.beat').on('click', function(e) {
                $.get('/game/beat/' + $(e.target).find('input').attr('value') + '/' + {{ $item->bomb_id }} + '/' + !$(e.target).find('input').is(':checked'));
            });

            // star rating
            $('.rating-star').on('mouseover', function(e) {
                $('.rating-star').removeClass('fa-star');
                $('.rating-star').addClass('fa-star-o');
                var star = $(e.target).attr('id').replace(/star/, '');
                for(var x = star; x > 0; x--) {
                    $('#star' + x).removeClass('fa-star-o');
                    $('#star' + x).addClass('fa-star');
                }
            });
            $('.rating-star').on('mouseout', function(e) {
                $('.rating-star').removeClass('fa-star');
                $('.rating-star').addClass('fa-star-o');
                $('.rating-star.full').addClass('fa-star');
                $('.rating-star.full').removeClass('fa-star-o');
            });
            $('.rating-star').on('click', function(e) {
                e.preventDefault();
                var star = $(e.target).attr('id').replace(/star/, '');
                @if($resource == "games")
                    $.get('/user/rate/games/{{ $item->bomb_id }}/' + star);
                @elseif($resource == "platforms")
                    $.get('/user/rate/platforms/{{ $item->bomb_id }}/' + star);
                @endif

                $('.rating-star').removeClass('full');
                var star = $(e.target).attr('id').replace(/star/, '');
                for(var x = star; x > 0; x--) {
                    $('#star' + x).addClass('full');
                }
            });
        });
    </script>
@endsection