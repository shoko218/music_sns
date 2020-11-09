<!DOCTYPE html>
<html lang="ja">
    <head>
        @yield('head')
    </head>
    <body>
        <div id="wrapper">
            @yield('header')
            @include('components.msgs')
            <div id="app">
                <div @if(Auth::check()) class="contents" @endif>
                    @yield('content')
                </div>
            </div>
            @yield('footer')
        </div>
    </body>
</html>
