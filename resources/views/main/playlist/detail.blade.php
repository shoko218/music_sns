@extends('layouts.base')

@section('pagename')
プレイリスト
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
        @if ($repost_user_name==null)
            <show-playlist-component :playlist='@json($playlist)' :user-id='@json(Auth::user()->id)'></show-playlist-component>
            @else
            <show-playlist-component :playlist='@json($playlist)' :user-id='@json(Auth::user()->id)' :repost-user-name='@json($repost_user_name)'></show-playlist-component>
        @endif
@endsection

@include('layouts.footer')
