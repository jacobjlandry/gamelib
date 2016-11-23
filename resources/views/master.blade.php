@if(!isset($resource))
    <?php $resource = 'user'; ?>
@endif
<html>
    <head>
        <title>Game Library</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/morris.css') }}" />
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/morris.min.js') }}"></script>
        <script src="{{ asset('js/raphael.js') }}"></script>
    </head>
    <body>
        <div class="nav">
            <div class="item @if($resource == "platforms")active @endif"><a href="/list/platforms"><i class="fa fa-plug fa-4x"></i></a></div>
            <div class="item @if($resource == "games")active @endif"><a href="/list/games"><i class="fa fa-gamepad fa-4x"></i></a></div>
            <div class="item @if($resource == "user")active @endif"><a href="/"><i class="fa fa-user fa-4x"></i></a></div>
            <div class="item"><div class="searchbar" id="searchbar"><input type="text" id="term" /><a id="search" href="#"><i class="fa fa-search fa-4x"></i></a></div></div>
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
                e.preventDefault();
                @if($resource == "games")
                    if($('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms').css('display') != 'none') {
                        $('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms').hide();
                    }
                    else {
                        $('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms').show();
                        $('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms li').on('click', function(e2) {
                            $.get('/user/claim/{{ $resource }}/' + $(e.target).attr('id') + "/" + $(e2.target).attr('id'));
                            $('#' + $(e.target).attr('id') + 'Item .ribbon-wrapper-green').show();
                            $('#{{ $resource }}' + $(e.target).attr('id') + 'Platforms').hide();
                        });
                    }
                @else
                    $.get('/user/claim/{{ $resource }}/' + $(e.target).attr('id')).complete(function(data) {
                        console.log('response');
                    });
                    $('#{{ $resource }}' + $(e.target).attr('id') + ' .fa').addClass('full');
                @endif
            });
        </script>
    </body>
</html>