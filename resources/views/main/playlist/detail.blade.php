@extends('layouts.base')

@section('pagename')
プレイリスト
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
<show-playlist-component :playlist='@json($playlist)'></show-playlist-component>
@endsection

@include('layouts.footer')
