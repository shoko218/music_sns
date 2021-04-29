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
                    <a href="/notice">
                        <li @if (Request::is('notice')||Request::is('notice/*')) class="selected_li" @endif><!--通知のページ内ならば選択デザインに切り替え-->
                            <p><i class="fas fa-bell"></i></p>
                        </li>
                    </a>
                </ul>
            </nav>
        </footer>
    @endif
@endsection
