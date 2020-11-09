<!DOCTYPE html>
<html lang="ja">
    <head>
        @yield('head')
    </head>
    <body>
        <div id="wrapper">
            <div id="app">
                @yield('header')
                @include('components.msgs')
                <div @if(Auth::check()) class="contents" @endif>
                    @yield('content')
                </div>
                @yield('footer')
            </div>
        </div>
    </body>
</html>
