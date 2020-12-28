@section('header')
    <div id="header">
        <p id="logo"><a href="/home">MusicS</a></p>
        <div id="header-btns">
            @if (Auth::check())
                <p><a href="/user/{{ Auth::user()->user_name }}"><i class="fas fa-user"></i></a></p>
                <p><a href="/logout"><i class="fas fa-sign-out-alt"></i></a></p>
            @else
                <p><a href="/login"><i class="fas fa-sign-in-alt"></i></a></p>
            @endif
        </div>

    </div>
@endsection
