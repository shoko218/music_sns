@section('footer')
    @if (Auth::check())
        <footer>
            <nav>
                <ul>
                    <a href="/home">
                        <li @if (Request::is('home')||Request::is('home/*')) class="selected_li" @endif><!--ホームのページ内ならば選択デザインに切り替え-->
                            <p><i class="fas fa-home"></i></p>
                        </li>
                    </a>
                    <a href="/playlist">
                        <li @if (Request::is('playlist')||Request::is('playlist/*')) class="selected_li" @endif><!--プレイリストホームのページ内ならば選択デザインに切り替え-->
                            <p><i class="fas fa-list-ol"></i></p>
                        </li>
                    </a>
                    <a href="/search">
                        <li @if (Request::is('search')||Request::is('search/*')) class="selected_li" @endif><!--通知のページ内ならば選択デザインに切り替え-->
                            <p><i class="fas fa-search"></i></p>
                        </li>
                    </a>
                    <a href="/notification">
                        <li @if (Request::is('notification')||Request::is('notification/*')) class="selected_li" @endif><!--通知のページ内ならば選択デザインに切り替え-->
                            <p><i class="fas fa-bell"></i></p>
                        </li>
                    </a>
                    <a href="/dm">
                        <li @if (Request::is('dm')||Request::is('dm/*')) class="selected_li" @endif><!--dmのページ内ならば選択デザインに切り替え-->
                            <p><i class="fas fa-envelope"></i></p>
                        </li>
                    </a>
                </ul>
            </nav>
        </footer>
    @endif
@endsection
