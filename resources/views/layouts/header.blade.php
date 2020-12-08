@section('header')
    <div id="header">
        <p id="logo"><a href="/home">MusicS</a></p>
        @if (Auth::check())
        <div>
            <a href="/user/{{ Auth::user()->user_name }}">マイページ</a>
            <a href="/logout">ログアウト</a>
        </div>
        @else
            <a href="/login">ログイン</a>
        @endif

    </div>
@endsection
