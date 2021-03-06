<?php
if(env('APP_ENV')==='production'){
    $is_production=true;
}else{
    $is_production=false;
}?>
@section('head')
    <meta charset="UTF-8">
    <title>@yield('pagename')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://kit.fontawesome.com/7791d4487f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all" />
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('js')
@endsection
