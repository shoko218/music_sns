@section('footer')
    @if (Auth::check())
        <footer>
            <nav>
                <ul>
                    <a href="/home"><li @if (Request::is('home')||Request::is('home/*')) class="selected_li" @endif><p><i class="fas fa-home"></i></p></li></a>
                    <a href="/playlist"><li @if (Request::is('playlist')||Request::is('playlist/*')) class="selected_li" @endif><p><i class="fas fa-list-ol"></i></p></li></a>
                    <a href="/notification"><li @if (Request::is('notification')||Request::is('notification/*')) class="selected_li" @endif><p><i class="fas fa-bell"></i></p></li></a>
                    <a href="/mypage"><li @if (Request::is('mypage')||Request::is('mypage/*')) class="selected_li" @endif><p><i class="fas fa-user"></i></p></li></a>
                </ul>
            </nav>
        </footer>
    @endif
@endsection
