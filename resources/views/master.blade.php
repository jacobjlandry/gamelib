<html>
    <head>
        <title>Game Library</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    </head>
    <body>
        <div class="nav">
            <ul class="nav">
                <li class="@if($resource == "platforms")active @endif"><a href="/list/platforms"><i class="fa fa-plug fa-4x"></i></a></li>
                <li class="@if($resource == "games")active @endif"><a href="/list/games"><i class="fa fa-gamepad fa-4x"></i></a></li>
                <li><a href="/login"><i class="fa fa-user fa-4x"></i></a></li>
                <li><a href=""><i class="fa fa-search fa-4x"></i></a></li>
            </ul>
        </div>
        <div class="maincontent">
            @yield('content')
        </div>
    </body>
</html>