@section('header')
    <div id="header">
        <p id="logo">logo</p>
        @if (Auth::check())
            <a href="/logout">ログアウト</a>
        @else
            <a href="/login">ログイン</a>
        @endif
    </div>
@endsection
