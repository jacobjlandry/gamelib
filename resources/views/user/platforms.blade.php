@extends('master')

@section('content')
    <div class="list-container">
        <div class="list">
            @foreach($platforms as $platform)
                <div class="item">
                    <a href="/user/games/{{ $platform->platform_id }}">
                        <div class="image">
                            <div class="img">
                                @if($platform->platform()->image)
                                    <img src="{{ $platform->platform()->image }}" width="125" />
                                @else
                                    <i class="fa fa-chain-broken fa-4x"></i>
                                @endif
                            </div>
                        </div>
                        <div class="title">
                            {{ $platform->platform()->name }} ({{ $platform->games()->count() }})
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection