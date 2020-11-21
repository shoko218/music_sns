<!DOCTYPE html>
<html lang="ja">
    <head>
        @yield('head')
        @stack('js')
    </head>
    <body>
        <div id="wrapper">
            <div id="app">
                @yield('header')
                <div @if(Auth::check()) class="contents" @endif>
                    @include('components.msgs')
                    @yield('content')
                </div>
                @yield('footer')
            </div>
        </div>
    </body>
</html>
