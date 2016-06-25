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
                <li><div class="searchbar" id="searchbar"><input type="text" id="term" /><a id="search" href="#"><i class="fa fa-search fa-4x"></i></a></div></li>
            </ul>
        </div>
        <div class="maincontent">
            @yield('content')
        </div>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // submit search via button
            $('#search').on('click', function(e) {
                if($('#term').val()) {
                    window.location = '/search/' + $('#term').val();
                }
            });

            // allow enter to submit text field
            $('#term').on('keypress', function(e) {
                if(e.keyCode == 13) {
                    $('#search').click();
                }
            });

            // allow clicking anywhere on search bar to activate text field
            $('#searchbar').on('click', function(e) {
                console.log('on it!');
                $('#term').focus();
                $('#term').select();
            })

            $('.claim').on('click', function(e) {
                $('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms').show();
                $('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms li').on('click', function(e2) {
                    $.get('/user/claim/{{ $resource }}/' + $(e.target).attr('id') + "/" + $(e2.target).attr('id'));
                    $('#{{ $resource }}' + $(e.target).attr('id') + ' .fa').addClass('full');
                    $('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms').hide();
                });
            });
        </script>
    </body>
</html>