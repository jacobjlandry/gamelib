@extends('master')
@section('content')
    <div class="list-container">
        <div class="list">
            @foreach($list as $item)
                <div class="item">
                    <a href="/basic/{{ $resource }}/{{ $item->id }}">
                        <div class="image">
                            @if(is_object($item->image))
                                <img src="{{ $item->image->thumb_url }}" />
                            @else
                                <i class="fa fa-chain-broken fa-4x"></i>
                            @endif
                        </div>
                        <div class="title">
                            {{ $item->name }}
                        </div>
                        <div id="{{ $item->id }}" class="controls">
                            <a href="#"><i class="toss fa fa-minus-circle @if(Auth::user()->has($resource, $item->id))full @endif" id="{{ $item->id }}"></i></a>
                            <a href="#"><i class="fa {{ Auth::user()->littleRating($resource, $item->id) }}"></i></a>
                            <a href="#"><i class="claim fa fa-plus-circle @if(Auth::user()->has($resource, $item->id))full @endif" id="{{ $item->id }}"></i></a>
                        </div>
                    </a>
                    <div id="{{ $resource }}{{ $item->id }}Platforms" class="platform-list">
                        @if(isset($item->platforms) && is_array($item->platforms))
                            <ul>
                                @foreach($item->platforms as $platform)
                                    <li id="{{ $platform->id }}">{{ $platform->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <li>
                <a href="@if($page != 1){{ preg_replace("/$page$/", $page-1, Request::url()) }} @else # @endif" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li><a href="#">Page {{ $page }} of {{ ceil($max_results / $page_limit) }}</a></li>
            <li>
                <a href="{{ preg_replace("/$page$/", $page+1, Request::url()) }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>
@endsection